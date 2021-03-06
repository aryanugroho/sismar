<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller{
	
	public function __construct(){
		
		parent::__construct();
		if(!$this->session->userdata('is_login'))redirect('login');
		if(!$this->general->privilege_check(CPROJECT,'view'))
		    $this->general->no_access();
		$this->session->set_userdata('menu','client');	
		$this->load->model('project_model');
	}
	
	public function index(){
	    
	    $data = array('title'=>'List Client - Tekno Power');
	    $this->_render('project',$data);
		
	}
	private function _render($view,$data = array()){
	    
	    $this->load->view('header',$data);
	    $this->load->view('sidebar');
	    $this->load->view($view,$data);
	    $this->load->view('footer');
	}
	public function add(){
	     
	     if(!$this->general->privilege_check(CPROJECT,'add'))
		    $this->general->no_access();
	     
	     $data = array('title'=>'Add Project - Tekno Power');
	     $this->_render('add_project',$data);
	}
	public function edit(){
	     
	     if(!$this->general->privilege_check(CPROJECT,'edit'))
		    $this->general->no_access();
		    
	     $id  = $this->uri->segment(4);
	     $data= array('title'=>'Edit Project - Tekno Power','id_project'=>$id);
	     
	     $prj = $this->project_model->get_edit($id);
	     if(!$prj)
	        show_error("Project Not found");
	        
	     $this->_render('edit_project',array_merge($data,$prj));
	}
	public function get_data(){
	    	    
	    $limit = $this->config->item('limit');
	    $offset= $this->uri->segment(4,0);
	    $q     = isset($_POST['q']) ? $_POST['q'] : '';	    
	    $data  = $this->project_model->get_data($offset,$limit,$q);
	    $rows  = $paging = '';
	    $total = $data['total'];
	    
	    if($data['data']){
	        
	        $i= $offset+1;
	        $j= 1;
	        foreach($data['data'] as $r){
	            
	            $rows .='<tr>';
	                
	                $rows .='<td>'.$i.'</td>';
	                $rows .='<td width="30%">'.$r->nama_project.'</td>';
	                $rows .='<td width="40%">'.$r->deskripsi.'</td>';
	                $rows .='<td width="30%" align="center">';
	                
	                $rows .='<a title="Edit" class="a-warning" href="'.base_url().'client/project/edit/'.$r->id_project.'">
	                            <i class="fa fa-pencil"></i> Edit
	                        </a> ';
	                $rows .='<a title="Delete" class="a-danger" href="'.base_url().'client/project/delete/'.$r->id_project.'">
	                                <i class="fa fa-times"></i> Delete
	                            </a> ';
	               
	               $rows .='</td>';
	            
	            $rows .='</tr>';
	            
	            ++$i;
	            ++$j;
	        }
	        
	        $paging .= '<li><span class="page-info">Displaying '.($j-1).' Of '.$total.' items</span></i></li>';
            $paging .= $this->_paging($total,$limit);
	        	       	        
	    	    
	    }else{
	        
	        $rows .='<tr>';
	            $rows .='<td colspan="6">No Data</td>';
	        $rows .='</tr>';
	        
	    }
	    
	    echo json_encode(array('rows'=>$rows,'total'=>$total,'paging'=>$paging));
	}
	
	private function _paging($total,$limit){
	
	    $config = array(
                
            'base_url'  => base_url().'client/project/get_data/',
            'total_rows'=> $total, 
            'per_page'  => $limit,
			'uri_segment'=> 4
        
        );
        $this->pagination->initialize($config); 

        return $this->pagination->create_links();
	}
	
	public function save(){
	    
	     $data = $this->input->post(null,true);
	     
	     $send = $this->project_model->save($data);
	     if($send)
	        redirect('client/project');
	}
	
	public function update(){
	    
	     $data = $this->input->post(null,true);
	     
	     $send = $this->project_model->update($data);
	     if($send)
	        redirect('client/project');
	}
	
	public function delete(){
	    
	     if(!$this->general->privilege_check(CPROJECT,'remove'))
		    $this->general->no_access();
		    
	     $id  = $this->uri->segment(4);
	     $send = $this->project_model->delete($id);
	     if($send)
	        redirect('client/project');
	}
	


}
