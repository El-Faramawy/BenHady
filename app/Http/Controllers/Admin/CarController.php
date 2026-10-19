<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Car\StoreCarRequest;
use App\Http\Requests\Admin\Car\UpdateCarRequest;
use App\Http\Requests\Admin\Common\MultiDeleteRequest;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FuelType;
use App\Models\ModelYear;
use App\Models\Transmission;
use App\Services\Car\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarController extends Controller
{
    public function __construct(protected CarService $carService)
    {
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->carService->getDataTable();
        }

        return view('Admin.Car.index');
    }

    public function create(): View
    {
        $brands = Brand::where('is_active', true)->orderBy('name_ar')->get();
        $categories = Category::where('is_active', true)->orderBy('name_ar')->get();
        $branches = Branch::where('is_active', true)->orderBy('name_ar')->get();
        $modelYears = ModelYear::where('is_active', true)->orderBy('year', 'desc')->get();
        $transmissions = Transmission::where('is_active', true)->orderBy('name_ar')->get();
        $fuelTypes = FuelType::where('is_active', true)->orderBy('name_ar')->get();
        $features = Feature::where('is_active', true)->orderBy('name_ar')->get();

        return view('Admin.Car.parts.create', compact(
            'brands',
            'categories',
            'branches',
            'modelYears',
            'transmissions',
            'fuelTypes',
            'features'
        ));
    }

    public function store(StoreCarRequest $request): JsonResponse
    {
        $data = $request->safe()->except(['features', 'branches', 'primary_image', 'gallery_images']);
        $features = (array) $request->input('features', []);
        $branches = (array) $request->input('branches', []);
        $primaryImage = $request->file('primary_image');
        $galleryImages = (array) $request->file('gallery_images', []);

        $this->carService->store($data, $primaryImage, $galleryImages, $features, $branches);

        return response()->json(['message' => __('messages.general.created_success')]);
    }

    /**
     * Show comprehensive Car Details page.
     */
    public function show(Car $car): View
    {
        $car = $this->carService->getCarWithFullDetails($car);

        return view('Admin.Car.show', compact('car'));
    }

    public function edit(Car $car): View
    {
        $car->load(['features', 'branches', 'images']);

        $brands = Brand::where('is_active', true)->orderBy('name_ar')->get();
        $categories = Category::where('is_active', true)->orderBy('name_ar')->get();
        $branches = Branch::where('is_active', true)->orderBy('name_ar')->get();
        $modelYears = ModelYear::where('is_active', true)->orderBy('year', 'desc')->get();
        $transmissions = Transmission::where('is_active', true)->orderBy('name_ar')->get();
        $fuelTypes = FuelType::where('is_active', true)->orderBy('name_ar')->get();
        $features = Feature::where('is_active', true)->orderBy('name_ar')->get();

        return view('Admin.Car.parts.edit', compact(
            'car',
            'brands',
            'categories',
            'branches',
            'modelYears',
            'transmissions',
            'fuelTypes',
            'features'
        ));
    }

    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        $data = $request->safe()->except(['features', 'branches', 'primary_image', 'gallery_images', 'delete_images']);
        $features = (array) $request->input('features', []);
        $branches = (array) $request->input('branches', []);
        $primaryImage = $request->file('primary_image');
        $galleryImages = (array) $request->file('gallery_images', []);
        $deleteImageIds = (array) $request->input('delete_images', []);

        $this->carService->update(
            $car,
            $data,
            $primaryImage,
            $galleryImages,
            $features,
            $branches,
            $deleteImageIds
        );

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    public function destroy(Car $car): JsonResponse
    {
        $this->carService->delete($car);

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    public function multiDelete(MultiDeleteRequest $request): JsonResponse
    {
        $this->carService->deleteMultiple($request->validated('ids'));

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    /**
     * Delete an individual image from a car.
     */
    public function deleteImage(Car $car, int $imageId): JsonResponse
    {
        $deleted = $this->carService->deleteImage($car, $imageId);

        if (!$deleted) {
            return response()->json(['message' => 'تعذر حذف الصورة'], 400);
        }

        return response()->json(['message' => __('messages.general.deleted_success')]);
    }

    /**
     * Set a specific car image as the primary cover image.
     */
    public function setPrimaryImage(Car $car, int $imageId): JsonResponse
    {
        $updated = $this->carService->setPrimaryImage($car, $imageId);

        if (!$updated) {
            return response()->json(['message' => 'تعذر تعيين الصورة الرئيسية'], 400);
        }

        return response()->json(['message' => __('messages.general.updated_success')]);
    }

    /**
     * Visual catalog view showcasing all cars.
     */
    public function detailsCatalog(): View
    {
        $cars = $this->carService->getAllCarsForCatalog();

        return view('Admin.Car.catalog', compact('cars'));
    }
}
