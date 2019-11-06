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
class Invoice
{
    private $bc = null;
    
    public function setBusinessControl($bc)
    {
        $this->bc = $bc;
    }
    
    public function all($page = 1, $params = []) {
        $params['page'] = $page;
        $query = http_build_query($this->bc->deleteEmptyValues($params));
        return $this->bc->get('/api/1.0/invoice/?' . $query);
    }
    
    public function nextInvoiceNumber($year = null, $companyId = null) {
        $query = "year=" . ($year === null ? date('Y') : $year);
        if($companyId !== null) {
            $query = "&company_id=" . $companyId;
        }
        return $this->bc->get('/api/1.0/invoice/next-number?' . $query);
    }
    
    public function create($data = [
            'company_id' => null, 'team_id' => null, 'client_id' => null, 'title' => null, 'description' => null, 'invoice_number' => null, 'invoice_date' => null, 'invoice_sent' => null, 'invoice_expire' => null, 'invoice_approved' => null, 'invoice_rejected' => null, 'invoice_completed' => null, 'invoice_lines' => []]) 
    {
        return $this->bc->post('/api/1.0/invoice', $data);
    }
    
    public function export($id)
    {
        return $this->bc->get('/api/1.0/invoice/' . $id . '/export', true);
    }
    
    public function updateInvoiceCompleted($id, $date = null)
    {
        if($date === null) {
            $date = date('Y-m-d H:i:s');
        }
        return $this->bc->post('/api/1.0/invoice/' . $id . '/invoice_completed', [
            'invoice_completed' => $date,
        ]);
    }
    
    public function updateStatus($id, $status)
    {
        return $this->bc->post('/api/1.0/invoice/' . $id . '/status', [
            'status' => $status,
        ]);
    }
    
    public function detail($id)
    {
        return $this->bc->get('/api/1.0/invoice/' . $id);
    }
    
    public function delete($id)
    {
        return $this->bc->delete('/api/1.0/invoice/' . $id, $data);
    }
}