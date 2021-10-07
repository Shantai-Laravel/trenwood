<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
      $reviews = Review::with('translation')->orderBy('position', 'desc')->get();

      return view('admin::admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
      return view('admin::admin.reviews.create');
    }


    public function store(Request $request)
    {
      $toValidate = [];
      foreach ($this->langs as $lang){
          $toValidate['author_'.$lang->lang] = 'required|max:255';
          $toValidate['text_'.$lang->lang] = 'required';
      }

      $validator = $this->validate($request, $toValidate);

      if ( $request->avatar) {
          $name = time() . '-' . $request->avatar->getClientOriginalName();
          $request->avatar->move(storage_path('upfiles/reviews/'), $name);
      }else{
          $name = "noname.jpg";
      }

      $review = new Review();
      $review->img = $name;
      $review->position = 1;
      $review->active = 1;
      $review->save();

      foreach ($this->langs as $lang):
          $review->translations()->create([
              'lang_id' => $lang->id,
              'author' => request('author_' . $lang->lang),
              'text' => request('text_' . $lang->lang),
              'alt' => request('alt_' . $lang->lang),
              'title' => request('title_' . $lang->lang)
          ]);
      endforeach;

      session()->flash('message', 'New item has been created!');

      return redirect()->route('review.index')->withInput();
    }

    public function edit($id)
    {
      $review = Review::with('translations')->findOrFail($id);
      return view('admin::admin.reviews.edit', compact('review', 'translations'));
    }


    public function update(Request $request, $id)
    {
      $toValidate = [];
      foreach ($this->langs as $lang){
          $toValidate['author_'.$lang->lang] = 'required|max:255';
          $toValidate['text_'.$lang->lang] = 'required';
      }

      $validator = $this->validate($request, $toValidate);

      $review = Review::findOrFail($id);

      if ($request->file('avatar')) {
          $name = time() . '-' . $request->avatar->getClientOriginalName();
          $request->avatar->move(storage_path('upfiles/reviews/'), $name);

          if (file_exists(storage_path('upfiles/reviews/') . $review->img)) {
              unlink(storage_path('upfiles/reviews/') . $review->img);
          }

          $review->img = $name;
      }

      $review->position = 1;
      $review->active = 1;
      $review->save();

      $review->translations()->delete();

      foreach ($this->langs as $lang):
          $review->translations()->create([
              'lang_id' => $lang->id,
              'author' => request('author_' . $lang->lang),
              'text' => request('text_' . $lang->lang),
              'alt' => request('alt_' . $lang->lang),
              'title' => request('title_' . $lang->lang)
          ]);
      endforeach;

      session()->flash('message', 'Item has been updated!');

      return redirect()->route('review.index');
    }

    public function changeStatus($id)
    {
        $review = Review::findOrFail($id);

        if ($review->active == 1) {
            $review->active = 0;
        } else {
            $review->active = 1;
        }

        $review->save();

        return redirect()->route('review.index');
    }

    public function destroy($id)
    {
      $review = Review::findOrFail($id);

      if (file_exists(storage_path('upfiles/reviews/') . $review->img)) {
          unlink(storage_path('upfiles/reviews/') . $review->img);
      }

      $review->delete();

      $review->translations()->delete();

      session()->flash('message', 'Item has been deleted!');

      return redirect()->route('review.index');
    }
}
