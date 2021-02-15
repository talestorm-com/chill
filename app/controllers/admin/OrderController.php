<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of OrderController
 *
 * @author studio2
 */
class OrderController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.Orders";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        $cond = \ADVTable\Filter\FixedTokenFilter::F(NULL, [
                    'id' => 'Int:A.id',
                    'reserve' => 'Int:A.reserve',
                    'shop' => 'Int:A.shop_id',
                    'created' => 'Date:A.created',
                    'user_wrap_user_name' => 'String:A.user_name',
                    'user_wrap_user_phone' => 'String:A.user_phone',
                    'user_wrap_user_email' => 'String:A.user_email',
                    'user_wrap_dealer' => 'Int:A.dealer',
                    'position' => 'Int:C.position',
                    'amount' => 'Numeric:C.amount',
                    'status' => 'Int:B.status',
                    'user_id' => 'Int:A.user_id',
        ]);
        $dir = \ADVTable\Sort\FixedTokenSort::F(null, [
                    'id' => 'A.id',
                    'reserve' => 'A.reserve|A.id',
                    'shop' => 'A.shop_name|A.id',
                    'created' => 'A.created,A.id',
                    'user_wrap_user_name' => 'A.user_name|A.id',
                    'user_wrap_user_email' => 'A.user_email|A.id',
                    'user_wrap_user_phone' => 'A.user_phone|A.id',
                    'user_wrap_dealer' => 'A.dealer|A.id',
                    'position' => 'C.position|A.id',
                    'amount' => 'C.amount|A.id',
                    'status' => 'B.status|A.id',
        ]);
        $dir->tokens_separator = "|";
        $lim = \ADVTable\Limit\FixedTokenLimit::F();
        $c = 0;
        $p = [];
        $where = $cond->buildSQL($p, $c);
        $q = "SELECT SQL_CALC_FOUND_ROWS A.id,A.reserve,A.shop_name shop,DATE_FORMAT(A.created,'%%d.%%m.%%Y')created,
            A.user_name,A.user_phone,A.user_email,A.dealer,A.delivery,B.status,C.position,C.amount
            FROM clientorder A LEFT JOIN clientorder__status B ON(B.id=A.id)
            LEFT JOIN clientorder__total C ON(C.id=A.id)
            %s %s %s %s;
            ";
        $rq = sprintf($q, $cond->whereWord, $where, $dir->SQL, $lim->MySqlLimit);
        $items = \DB\DB::F()->queryAll($rq, $p);
        if (!count($items) && $lim->page) {
            $lim->setPage(0);
            $rq = sprintf($q, $cond->whereWord, $where, $dir->SQL, $lim->MySqlLimit);
            $items = \DB\DB::F()->queryAll($rq, $p);
        }
        $total = \DB\DB::F()->get_found_rows();
        $this->out->add("items", $items)->add("total", $total)->add("page", $lim->page)->add("perpage", $lim->perpage);
    }

    protected function API_set_order_status() {
        $id = $this->GP->get_filtered('order_id', ['IntMore0', 'DefaultNull']);
        $status = $this->GP->get_filtered('status', ['IntMore0', 'Default0']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("UPDATE clientorder__status set status=:P WHERE id=:PP;");
        $b->push_params([
            ":P" => $status,
            ":PP" => $id,
        ]);
        $b->execute_transact();
    }

    protected function API_remove() {
        $id = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push("DELETE FROM clientorder WHERE id=:P");
        $b->push_param(":P", $id);
        $b->execute_transact();
        $this->API_list();
    }

    protected function API_get() {
        $id = $this->GP->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $order = \Content\Order\Order::F($id);
        $order && $order->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add("order", $order);
    }

}
