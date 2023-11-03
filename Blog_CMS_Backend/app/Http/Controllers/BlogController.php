<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Repositories\BlogRepository;

class BlogController extends BaseController
{
    private $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        parent::__construct();

        $this->blogRepository = $blogRepository;
    }


    /**
     * Get all blogs by custom search and pagination
     *
     * @author Htoo Maung Thait
     * @since 2023-11-03
     *
     * @param Request $request['page', 'limit', 'search']
     * @return json
     */
    public function index(Request $request) {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $search = $request->input('search', []);

        if(!is_array($search)){
            $search = json_decode($search, true);
        }



        try{
            $data = $this->blogRepository->getAll($page, $limit, $search);

            if($data){
                $this->setResponseInfo('success', 'Blogs can be queried successfully,', [], '', '');
            }
            else{
                $this->setResponseInfo('no data','', [],'No blog can be found', '');
            }

        }catch(Throwable $th){
            $this->setResponseInfo('error', '', '','', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);


    }

    public function store(Request $request) {}

    public function updateById(Request $request, $id) {}


    /**
     * Delete Blog by Id
     * @author Htoo Maung Thait
     * @since 2023-11-03
     *
     * @param int $id
     * @return json
     */
    public function deleteById($id) {
        if(!$id){
            $this->setResponseInfo('invalid', ['id' => 'no id is provided'], '','', '');
            return response()->json($this->response, $this->httpStatus);
        }
        try{
            $status = $this->blogRepository->deleteById($id);

            if($status){
                $this->setResponseInfo('success', 'Blog deleted successfully', [], '', '');
            }
            else{
                $this->setResponseInfo('no data','', [],'No blog can be found to delete', '');
            }
        }catch(Throwable $th){
            $this->setResponseInfo('error', '', '','', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * Search Blog by Id
     * @author Htoo Maung Thait
     * @since 2023-11-03
     *
     * @param int $id
     * @return json
     */
    public function getById($id) {
        if(!$id){
            $this->setResponseInfo('invalid', ['id' => 'no id is provided'], '','', '');
            return response()->json($this->response, $this->httpStatus);
        }
        try{
            $status = $this->blogRepository->showById($id);

            if($status){
                $this->setResponseInfo('success', 'Blog is queried successfully', [], '', '');
            }
            else{
                $this->setResponseInfo('no data','', [],'No blog can be found ', '');
            }
        }catch(Throwable $th){
            $this->setResponseInfo('error', '', '','', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }


}

