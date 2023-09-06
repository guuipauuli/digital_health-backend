<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;

class RequestDataExtractor
{
    private Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    private function getUrlData()
    {
        $queryData = $this->request->query->all();
        $orderData = array_key_exists('sort', $queryData) ? $queryData['sort'] : [];
        unset($queryData['sort']);
        $paginationData = array_key_exists('page', $queryData) ? $queryData['page'] : 1;
        unset($queryData['page']);
        $itemsPerPage = array_key_exists('perPage', $queryData) ? $queryData['perPage'] : null;
        unset($queryData['perPage']);
        foreach ($queryData as $key => $value){
            if($value === 'null'){
                $queryData[$key] = null;
            }
        }

        return [$queryData, $orderData, $paginationData, $itemsPerPage];
    }

    public function getFilterData()
    {
        [$filterData, , ] = $this->getUrlData();
        foreach($filterData as $key => $filter){
            if(is_string($filter) && str_contains($filter, "[") && str_contains($filter, "]")){
                $filterData[$key] = explode(",", str_replace(["[", "]"], "", $filter));
            }
        }
        return $filterData;
    }

    public function getOrderData()
    {
        [, $orderData, ] = $this->getUrlData();
        return $orderData;
    }

    public function getPaginationData()
    {
        [, , $paginationData] = $this->getUrlData();
        if($paginationData > 0){
            return $paginationData * $this->getItemsPerPage();
        }
        return $paginationData;
    }

    public function getItemsPerPage()
    {
        [, , , $itemsPerPage] = $this->getUrlData();
        return $itemsPerPage;
    }
}
