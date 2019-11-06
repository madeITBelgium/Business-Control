<?php

namespace MadeITBelgium\BusinessControl\Object;


/**
 * Business Control PHP SDK
 *
 * @version    0.0.1
 *
 * @copyright  Copyright (c) 2018 Made I.T. (http://www.madeit.be)
 * @author     Made I.T. <info@madeit.be>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-3.txt    LGPL
 */
class Offer
{
    private $bc = null;
    
    public function setBusinessControl($bc)
    {
        $this->bc = $bc;
    }
    
    public function all($page = 1, $params = []) {
        $params['page'] = $page;
        $query = http_build_query($this->bc->deleteEmptyValues($params));
        return $this->bc->get('/api/1.0/offer/?' . $query);
    }
    
    public function nextOfferNumber($year = null, $companyId = null) {
        $query = "year=" . ($year === null ? date('Y') : $year);
        if($companyId !== null) {
            $query = "&company_id=" . $companyId;
        }
        return $this->bc->get('/api/1.0/offer/next-number?' . $query);
    }
    
    public function create($data = []) 
    {
        return $this->bc->post('/api/1.0/offer', $data);
    }
    
    public function export($id)
    {
        return $this->bc->get('/api/1.0/offer/' . $id . '/export', true);
    }
    
    public function updateStatus($id, $status)
    {
        return $this->bc->post('/api/1.0/offer/' . $id . '/status', [
            'status' => $status,
        ]);
    }
    
    public function detail($id)
    {
        return $this->bc->get('/api/1.0/offer/' . $id);
    }
    
    public function delete($id)
    {
        return $this->bc->delete('/api/1.0/offer/' . $id, $data);
    }
}