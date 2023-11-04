<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Repositories\BlogRepository;
use Dotenv\Validator;

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
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $search = $request->input('search', []);

        if (!is_array($search)) {
            $search = json_decode($search, true);
        }



        try {
            $data = $this->blogRepository->getAll($page, $limit, $search);

            if ($data) {
                $this->setResponseInfo('success', 'Blogs can be queried successfully,', [], '', '');
            } else {
                $this->setResponseInfo('no data', '', [], 'No blog can be found', '');
            }
        } catch (Throwable $th) {
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * Create Blog from api with optional image upload
     *
     * @author Htoo Maung Thait
     * @since 2023-11-04
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'short_content' => 'required|string',
            'content_body' => 'required|string',
            'image_file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:draft,published,archived',
            'author_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->setResponseInfo('invalid', '', $validator->errors()->toArray(), '', '');
            return response()->json($this->response, $this->httpStatus);
        }

        try {
            $validatedData = $validator->validated();

            if($request->hasFile('image_file')){
                $imageName = uniqid() . '.' . $request->image_file->extension();

                $validatedData['image_file'] = $imageName;

                $request->image_file->move(public_path('storage/blog_imgs'), $imageName);
            }





            $blog = $this->blogRepository->create($validatedData);

            if ($blog) {
                $this->setResponseInfo('success', 'Blog created successfully', [], '', '');
            } else {
                $this->setResponseInfo('no data', '', [], 'No blog can be found', '');
            }
        } catch (Throwable $th) {
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }

    /**
     * Blog Update by blog with optional image upload
     *
     * @author Htoo Maung Thait
     * @since 2023-11-04
     *
     * @param Request $request
     * @param int $id
     * @return json
     */
    public function updateById(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:blogs,id',
            'title' => 'required|string',
            'short_content' => 'required|string',
            'content_body' => 'required|string',
            'image_file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:draft,published,archived',
            'author_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->setResponseInfo('invalid', '', $validator->errors()->toArray(), '', '');
            return response()->json($this->response, $this->httpStatus);
        }

        try{
            $blog = $this->blogRepository->showById($id);

            if($request->hasFile('image_file')){
                $imageName = uniqid() . '.' . $request->image_file->extension();

                $validatedData['image_file'] = $imageName;

                $oldImageName = $blog->image_file;
                $imagePath = public_path('storage/blog_imgs/'.$oldImageName);
                unlink($imagePath);


                $request->image_file->move(public_path('storage/blog_imgs'), $imageName);
            }

            $status = $this->blogRepository->update($validatedData, $id);

            if($status){
                $this->setResponseInfo('success', 'Blog updated successfully', [], '', '');
            }
            else{
                $this->setResponseInfo('no data', '', [], 'No blog can be found to update', '');
            }

        }catch(Throwable $th){
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }


    /**
     * Delete Blog by Id
     * @author Htoo Maung Thait
     * @since 2023-11-03
     *
     * @param int $id
     * @return json
     */
    public function deleteById($id)
    {
        if (!$id) {
            $this->setResponseInfo('invalid', ['id' => 'no id is provided'], '', '', '');
            return response()->json($this->response, $this->httpStatus);
        }
        try {
            $status = $this->blogRepository->deleteById($id);

            if ($status) {
                $this->setResponseInfo('success', 'Blog deleted successfully', [], '', '');
            } else {
                $this->setResponseInfo('no data', '', [], 'No blog can be found to delete', '');
            }
        } catch (Throwable $th) {
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
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
    public function getById($id)
    {
        if (!$id) {
            $this->setResponseInfo('invalid', ['id' => 'no id is provided'], '', '', '');
            return response()->json($this->response, $this->httpStatus);
        }
        try {
            $status = $this->blogRepository->showById($id);

            if ($status) {
                $this->setResponseInfo('success', 'Blog is queried successfully', [], '', '');
            } else {
                $this->setResponseInfo('no data', '', [], 'No blog can be found ', '');
            }
        } catch (Throwable $th) {
            $this->setResponseInfo('error', '', '', '', $th->getMessage());
        }

        return response()->json($this->response, $this->httpStatus);
    }
}
