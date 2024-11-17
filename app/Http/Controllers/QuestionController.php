<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionComponent;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function componentIndex()
    {
        confirmDelete("مطمئنید؟", "آیا از حذف این مورد اطمینان دارید؟");
        $action=request('item')?'update':'store';
        $item=$action=='update'?QuestionComponent::findOrFail(request('item')):null;
        $components=QuestionComponent::latest()->get();
        return view('question.component-index',compact('components','action','item'));
    }
    public function componentStore(Request $request)
    {

        if ($request->action=='store') {
            QuestionComponent::create([
                'name'=>$request->name,
                'description'=>$request->desc,
                'lang'=>app()->getLocale()
            ]);
            alert()->success('موفق','ذخیره شد');
            return back();
        }else{
            $component=QuestionComponent::findOrFail($request->item);
            // $component->name=$request->name;
            // $component->save;
            $component->update([
                'name'=>$request->name,
                'description'=>$request->desc,
            ]);
            alert()->success('موفق','ویرایش شد');
            return to_route('q-c.index');
        }
    }
    public function componentDelete(QuestionComponent $questionComponent) {
        $questionComponent->delete();
        alert()->success('موفق','حذف شد');
        return back();

    }

    public function index()
    {
        confirmDelete("مطمئنید؟", "آیا از حذف این مورد اطمینان دارید؟");
        $questions=Question::latest()->paginate('20');
        return view('question.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $components=QuestionComponent::latest()->get();
        $item=request('item')?Question::findOrFail(request('item')):null;
        return view('question.create',compact('components','item'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'body'=>'required'
        ]);
        if ($request->item) {
            $question=Question::findOrFail($request->item);
            $question->update([
                'title'=>$request->title,
                'body'=>$request->body,
                'component_id'=>$request->component
            ]);
            alert()->success('موفق','ویرایش شد');
        }else{
            Question::create([
                'title'=>$request->title,
                'body'=>$request->body,
                'component_id'=>$request->component,
                'lang'=>app()->getLocale()
            ]);
            alert()->success('موفق','ذخیره شد');
        }
        return to_route('question.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question=Question::findOrFail($id);
        $question->delete();
        alert()->success('موفق','حذف شد');
        return back();
    }
}
