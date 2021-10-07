<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\GalleryImageTranslation;
use App\Models\AutoAlt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class GalleriesController extends Controller
{
    public function index()
    {
        $galleries = Gallery::get();

        return view('admin::admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin::admin.galleries.create');
    }

    public function store(Request $request)
    {
        $toValidate['alias'] = 'required|max:255|unique:galleries';

        $validator = $this->validate($request, $toValidate);

        $gallery = new Gallery();
        $gallery->alias = request('alias');
        $gallery->save();

        $images = array();

        if($files = $request->file('images')){

            foreach($files as $key => $file){
                $hash = uniqid();
                $name = $hash.$file->getClientOriginalName();

                $image_resize = Image::make($file->getRealPath());

                $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];

                $image_resize->save(public_path('images/galleries/og/' .$name), 75);

                $image_resize->resize($gallery_image_size[0]['bgfrom'], $gallery_image_size[0]['bgto'])->save(public_path('images/galleries/bg/' .$name), 75);

                $image_resize->resize($gallery_image_size[1]['mdfrom'], $gallery_image_size[1]['mdto'])->save(public_path('images/galleries/md/' .$name), 75);

                $image_resize->resize($gallery_image_size[2]['smfrom'], $gallery_image_size[2]['smto'])->save(public_path('images/galleries/sm/' .$name), 85);

                $images[] = $name;

                $image = GalleryImage::create( [
                    'gallery_id' =>  $gallery->id,
                    'src' =>  $name
                ]);

                foreach ($this->langs as $lang){
                  $autoAlt = AutoAlt::where('cat_id', 0)->where('lang_id', $lang->id)->pluck('keywords')->toArray();
                    if(count($autoAlt) > 0) {
                      if (count($autoAlt) == 1) {
                          GalleryImageTranslation::create( [
                              'gallery_image_id' => $image->id,
                              'lang_id' =>  $lang->id,
                              'alt' => $autoAlt[0],
                              'title' => $autoAlt[0],
                              'text' => $request->text[$lang->id][$key],
                          ]);
                      } else {
                          GalleryImageTranslation::create( [
                              'gallery_image_id' => $image->id,
                              'lang_id' =>  $lang->id,
                              'alt' => $autoAlt[array_rand($autoAlt)],
                              'title' => $autoAlt[array_rand($autoAlt)],
                              'text' => $request->text[$lang->id][$key],
                          ]);
                      }
                   } else {
                     GalleryImageTranslation::create( [
                         'gallery_image_id' => $image->id,
                         'lang_id' =>  $lang->id,
                         'alt' => $request->text[$lang->id][$key],
                         'title' => $request->text[$lang->id][$key],
                         'text' => $request->text[$lang->id][$key],
                     ]);
                   }
                }
            }
        }

        Session::flash('message', 'New item has been created!');

        return redirect()->route('galleries.index');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);

        $images = GalleryImage::where('gallery_id', $id)->get();

        return view('admin::admin.galleries.edit', compact('gallery', 'images'));
    }

    public function update(Request $request, $id)
    {

        $toValidate['alias'] = 'required|max:255';

        $validator = $this->validate($request, $toValidate);

        $gallery = Gallery::findOrFail($id);
        $gallery->alias = request('alias');
        $gallery->save();

        $images = array();

        if($files = $request->file('images')){
            foreach($files as $key => $file){
                $uniq = uniqid();
                $name = $uniq.$file->getClientOriginalName();

                $image_resize = Image::make($file->getRealPath());

                $gallery_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['gallery'];

                $image_resize->save(public_path('images/galleries/og/' .$name), 75);

                $image_resize->resize($gallery_image_size[0]['bgfrom'], $gallery_image_size[0]['bgto'])->save(public_path('images/galleries/bg/' .$name), 75);

                $image_resize->resize($gallery_image_size[1]['mdfrom'], $gallery_image_size[1]['mdto'])->save(public_path('images/galleries/md/' .$name), 75);

                $image_resize->resize($gallery_image_size[2]['smfrom'], $gallery_image_size[2]['smto'])->save(public_path('images/galleries/sm/' .$name), 85);

                $images[] = $name;

                $image = GalleryImage::create( [
                    'gallery_id' =>  $id,
                    'src' =>  $name
                ]);

                foreach ($this->langs as $lang){
                  $autoAlt = AutoAlt::where('cat_id', 0)->where('lang_id', $lang->id)->pluck('keywords')->toArray();
                    if(count($autoAlt) > 0) {
                      if (count($autoAlt) == 1) {
                          GalleryImageTranslation::create( [
                              'gallery_image_id' => $image->id,
                              'lang_id' =>  $lang->id,
                              'alt' => $autoAlt[0],
                              'title' => $autoAlt[0],
                              'text' => $request->text[$lang->id][$key],
                          ]);
                      } else {
                          GalleryImageTranslation::create( [
                              'gallery_image_id' => $image->id,
                              'lang_id' =>  $lang->id,
                              'alt' => $autoAlt[array_rand($autoAlt)],
                              'title' => $autoAlt[array_rand($autoAlt)],
                              'text' => $request->text[$lang->id][$key],
                          ]);
                      }
                    } else {
                      GalleryImageTranslation::create( [
                          'gallery_image_id' => $image->id,
                          'lang_id' =>  $lang->id,
                          'alt' => $request->text[$lang->id][$key],
                          'title' => $request->text[$lang->id][$key],
                          'text' => $request->text[$lang->id][$key],
                      ]);
                    }
                }
            }
        }

        $inputs = $request->get('is_alt');

        if(!empty($inputs)){
            foreach($inputs as $key => $input){
                foreach ($this->langs as $lang){
                    GalleryImageTranslation::where('gallery_image_id', $key)->where('lang_id', $lang->id)->update( [
                        'alt' => $request->get('is_alt')[$key][$lang->id],
                        'title' => $request->get('is_title')[$key][$lang->id],
                        'text' => $request->get('is_text')[$key][$lang->id],
                    ]);
               }
            }
        }

        return redirect()->back();
    }


    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if (file_exists('/images/galleries' . $gallery->image)) {
            unlink('/images/galleries' . $gallery->image);
        }

        $gallery->delete();

        $images = GalleryImage::where('gallery_id', $gallery->id)->get();

        if (!empty($images)) {
            foreach ($images as $key => $image) {
                GalleryImage::where('id', $image->id)->delete();
                $imagesTrans = GalleryImageTranslation::where('gallery_image_id', $image->id)->delete();

                if (file_exists('/images/galleries/bg/'.$image->src)) {
                    unlink('/images/galleries/bg/'.$image->src);
                }
                if (file_exists('/images/galleries/og/'.$image->src)) {
                    unlink('/images/galleries/og/'.$image->src);
                }
                if (file_exists('/images/galleries/md/'.$image->src)) {
                    unlink('/images/galleries/md/'.$image->src);
                }
                if (file_exists('/images/galleries/sm/'.$image->src)) {
                    unlink('/images/galleries/sm/'.$image->src);
                }
            }
        }

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('galleries.index');
    }

    public function deleteGalleryImages(Request $request)
    {
        $image = GalleryImage::where('gallery_id', $request->get('galleryId'))->where('id', $request->get('id'))->first();
        GalleryImage::where('gallery_id', $request->get('galleryId'))->where('id', $request->get('id'))->delete();
        $images = GalleryImageTranslation::where('gallery_image_id', $request->get('id'))->get();

        if (!is_null($images)) {
            if (file_exists('/images/galleries/bg/'.$image->src)) {
                unlink('/images/galleries/bg/'.$image->src);
            }
            if (file_exists('/images/galleries/og/'.$image->src)) {
                unlink('/images/galleries/og/'.$image->src);
            }
            if (file_exists('/images/galleries/md/'.$image->src)) {
                unlink('/images/galleries/md/'.$image->src);
            }
            if (file_exists('/images/galleries/sm/'.$image->src)) {
                unlink('/images/galleries/sm/'.$image->src);
            }
        }

        if (!empty($images)) {
            foreach ($images as $key => $image) {
               GalleryImage::where('id', $image->id)->delete();
            }
        }

        return "true";
    }

}
