<?php

ob_start();
if (!headers_sent()) {
    header("Content-Type: application/json");
}
require_once __DIR__ . DIRECTORY_SEPARATOR . "phplib" . DIRECTORY_SEPARATOR . "ADVTable.php";
$OD = ['status' => 'ok', 'post' => $_POST];
try {
    $DB = new PDO("mysql:host=127.0.0.1;dbname=advtableTests", "root", "crysolite");
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $query = "SELECT SQL_CALC_FOUND_ROWS id val1,some col2,avesome aaa,theresome ddd FROM test1 A ";
    $limit = \ADVTable\Limit\MutableLimit::F(null)->setTokens("page", "perpage")->init();
    $order = \ADVTable\Sort\MutableSort::F(null, ['someColumn' => 'avesome', 'col1' => '`A`.`id`'])->setTokens("sortColumn", "sortDirection")->init();

    $where = \ADVTable\Filter\MutableFilter::F(null, [
                'col1' => 'Int:A.id',
                'col2_col4' => 'String:A.theresome',
                'col4' => 'Date:A.some'])->setFilterToken('filters');
    $counter = 0;
    $params = [];

    $query = "{$query} {$where->whereWord} {$where->buildSQL($params, $counter)} {$order->SQL} {$limit->MySqlLimit} ";
    $OD["query"] = $query;
    $OD['params']=$params;
    $statement = $DB->prepare($query);
    $statement->execute($params);
    $OD['items'] = $statement->fetchAll();
    $statement = $DB->prepare("SELECT FOUND_ROWS();");
    $statement->execute();

    $OD['perpage'] = $limit->perpage;
    $OD['page'] = $limit->page;
    $OD['total'] = intVal($statement->fetchColumn(0));
} catch (Exception $e) {
    $OD['status'] = 'error';
    $OD['error'] = $e->getMessage();
}


class a{
    use ADVTable\Util\TAccess;
    protected $val1;
    protected $col2;
    private $aaa;
    
    public function __construct() {
        ;
    }
    
    public static function F(){
        return new static;
    }
    
    
    protected function __get__val1(){
        return $this->val1;
    }
    
    protected function __set__val1($x){
        $this->val1 = $x;
    }
        
    
}

//$stmt = $DB->prepare("SELECT SQL_CALC_FOUND_ROWS id val1,some col2,avesome aaa FROM test1 A");
//$stmt->execute();
///var_dump($stmt->fetchAll(PDO::FETCH_CLASS,"a"));die();



die(json_encode($OD));

