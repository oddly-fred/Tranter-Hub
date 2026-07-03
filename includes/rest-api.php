<?php
if (!defined('ABSPATH')) exit;

class Tranter_REST_API {
    public static function init() { add_action('rest_api_init', [__CLASS__, 'routes']); }
    public static function routes() {
        register_rest_route('tranter-engine/v1', '/market', ['methods'=>'GET', 'callback'=>function(){ return ['market'=>Tranter_Market::current(), 'country'=>Tranter_Market::country_code()]; }, 'permission_callback'=>'__return_true']);
        register_rest_route('tranter-engine/v1', '/services', ['methods'=>'GET', 'callback'=>[__CLASS__, 'services'], 'permission_callback'=>'__return_true']);
    }
    public static function services($request) {
        $market = $request->get_param('market') ?: Tranter_Market::current();
        $posts = get_posts(['post_type'=>'tranter_service','numberposts'=>20,'post_status'=>'publish','meta_query'=>[['key'=>'_tranter_markets','value'=>'"'.$market.'"','compare'=>'LIKE']]]);
        return array_map(fn($p)=>['id'=>$p->ID,'title'=>$p->post_title,'slug'=>$p->post_name,'excerpt'=>$p->post_excerpt], $posts);
    }
}
