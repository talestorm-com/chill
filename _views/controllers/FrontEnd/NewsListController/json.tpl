{assign var='output_json' value=['items'=>$items,'total'=>$total,'page'=>$page,'perpage'=>$perpage,'paginator'=>$paginator]}
{$output_json|json_encode}