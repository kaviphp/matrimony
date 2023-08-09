<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertismentStoreRequest;
use App\Models\Advertisement;
use App\Models\AdvertisementPage;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $sort_search  = null;
        $ads    = Advertisement::with('user')->latest();

        if ($request->has('search')){
            $sort_search  = $request->search;
            $ads    = $ads->where('title', 'like', '%'.$sort_search.'%');
        }
        $ads = $ads->paginate(10);

        return view('admin.advertisement.index', compact('ads', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $pages = AdvertisementPage::all();
        return view('admin.advertisement.create', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertismentStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $advertisement = Advertisement::create($data);
        $advertisement->advertisementPages()->attach($request->input('page_id'));

        $images = array_filter($request->images);
        $urls = array_filter($request->url);

        foreach ($images as $key => $image) {
            $imageData = [
                'image' => $image ?? '',
                'url' => $urls[$key] ?? '',
            ];
            $advertisement->images()->create($imageData);
        }

        flash(translate('Advertisement has been stored successfully.'))->success();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Advertisement $ad)
    {
        $ad->load('advertisementPages', 'images');
        $pages = AdvertisementPage::all();
        return view('admin.advertisement.edit',
        compact([
            'ad', 'pages'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdvertismentStoreRequest $request
     * @param  \App\Models\Advertisement $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertismentStoreRequest $request, Advertisement $ad)
    {
        $data = $request->validated();

        $ad->update($data);

        // Get the selected page IDs from the request
        $selectedPages = $request->input('page_id', []);

        // Check if the advertisement has a valid ID before attaching the pages
        if ($ad->exists) {
            // Detach all pages and then attach the selected pages
            $ad->advertisementPages()->sync($selectedPages);
        }

        // Delete existing images and create new images
        $images = array_filter($request->images);
        $urls = array_filter($request->url);

        $ad->images()->delete();
        foreach ($images as $key => $image) {
            $imageData = [
                'image' => $image ?? '',
                'url' => $urls[$key] ?? '',
                'advertisement_id' => $ad->id, // Set the advertisement_id
            ];
            $ad->images()->create($imageData);
        }

        flash(translate('Advertisement has been updated successfully.'))->success();
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function delete(Advertisement $advertisement)
    {
        $advertisement->delete();

        flash(translate('Advertisement has been deleted successfully.'))->success();
        return redirect()->back();
    }
}
