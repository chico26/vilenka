<?php

function show_pager($resultsPerPage, $query, $isBackend=FALSE) {
    ### Forma query para el paginador 
    $CI = &get_instance();    
    $url_pager = base_url().$CI->uri->segment(1).'/'.$CI->uri->segment(2);
    $url_pager .= ($isBackend) ? '/'.$CI->uri->segment(3) : '' ;
    
    $search = get_search_terms();
    if($search!=''){
        $url_pager .= '/search/'.urlencode($search);    
    }
    
    $url_pager .= '/page';
    $currentPage = get_requested_page();
    $doctrine_pager['pagerLayout'] = new Doctrine_Pager_Layout(
                    new Doctrine_Pager(
                            $query,
                            $currentPage,
                            $resultsPerPage
                    ),
                    new Doctrine_Pager_Range_Sliding(
                            array('chunk' => 5)
                    ),
                    $url_pager . '/{%page_number}'
    );    
    $doctrine_pager['pagerLayout']->setTemplate(' <a href="{%url}">{%page}</a> ');
    $doctrine_pager['pagerLayout']->setSelectedTemplate('{%page}');
    ### Crea el paginador
    $pager = $doctrine_pager['pagerLayout']->getPager();    
    ### Ejecuta query
    $doctrine_pager['result'] = $doctrine_pager['pagerLayout']->execute();    
    ### Envia propiedades a data
    $doctrine_pager['num_results'] = $pager->getNumResults();
    $doctrine_pager['next_page'] = $pager->getNextPage();
    $doctrine_pager['prev_page'] = $pager->getPreviousPage();
    $doctrine_pager['have_to_paginate'] = $pager->haveToPaginate();
    $doctrine_pager['first_page'] = $pager->getFirstPage();
    $doctrine_pager['last_page'] = $pager->getLastPage();
    $doctrine_pager['get_page'] = $pager->getPage();
    $doctrine_pager['url_pager'] = $url_pager;
    ### Return
    return $doctrine_pager;
}

function show_pager_backend($resultsPerPage, $query) {
    ### Forma query para el paginador 
    $CI = &get_instance();    
    $url_pager = base_url().$CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$CI->uri->segment(3);
    
    $search = get_search_terms();
    if($search!=''){
        $url_pager .= '/search/'.urlencode($search);    
    }
    
    $url_pager .= '/page';
    $currentPage = get_requested_page();
    $doctrine_pager['pagerLayout'] = new Doctrine_Pager_Layout(
                    new Doctrine_Pager(
                            $query,
                            $currentPage,
                            $resultsPerPage
                    ),
                    new Doctrine_Pager_Range_Sliding(
                            array('chunk' => 5)
                    ),
                    $url_pager . '/{%page_number}'
    );    
    $doctrine_pager['pagerLayout']->setTemplate(' <a href="{%url}">{%page}</a> ');
    $doctrine_pager['pagerLayout']->setSelectedTemplate('{%page}');
    ### Crea el paginador
    $pager = $doctrine_pager['pagerLayout']->getPager();    
    ### Ejecuta query
    $doctrine_pager['result'] = $doctrine_pager['pagerLayout']->execute();    
    ### Envia propiedades a data
    $doctrine_pager['num_results'] = $pager->getNumResults();
    $doctrine_pager['next_page'] = $pager->getNextPage();
    $doctrine_pager['prev_page'] = $pager->getPreviousPage();
    $doctrine_pager['page_exists'] = $pager->haveToPaginate();
    $doctrine_pager['first_page'] = $pager->getFirstPage();
    $doctrine_pager['last_page'] = $pager->getLastPage();
    $doctrine_pager['get_page'] = $pager->getPage();
    $doctrine_pager['url_pager'] = $url_pager;
    ### Return
    return $doctrine_pager;
}


function display_pagination($paginator_object) {
    $CI = &get_instance();
    return $CI->load->view('template/pagination', $paginator_object, true);
}

function get_requested_page() {
    $CI = &get_instance();
    if(is_backend()){
         $uri = $CI->uri->uri_to_assoc(2);
    }else{
         $uri = $CI->uri->uri_to_assoc(1);
    }   
    if (isset($uri['page']) AND $uri['page'] !== FALSE){
        $page = $uri['page'];
    } else{
        $page = 1;
    }
    return $page;
}

function add_search_terms($query,array $fields_to_search_in){       
    $search = get_search_terms();
    if($search!=''){
    	$q = '';
    	foreach($fields_to_search_in as $field)
    	{
    		$q .= ($q == '') ? '' : ' OR ' ;
    		$q .= "($field LIKE '%$search%')";
    	}
        $query=$query->where($q);
    }        
    return $query;
}

function is_backend(){
    $CI = &get_instance();
     if($CI->uri->segment(1)=='backend'){
         return TRUE;
     }
     return FALSE;
     
}

function get_search_terms(){
    $search = '';
    $CI = &get_instance();
    $uri = $CI->uri->uri_to_assoc(1);    
    if(isset($_POST['search'])){
        $search= $_POST['search'];
    }else if(isset($uri['search'])&&$uri['search']!=''){
        $search = urldecode($uri['search']);
    }
    return $search;
}