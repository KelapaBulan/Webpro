<?php    
namespace App\Http\Controllers;
    
use App\Models\Keranjang;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class KeranjangController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         //$this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $products = Keranjang::latest()->paginate(5);
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function keranjang(): View
    {
        $keranjangs = Keranjang::latest()->paginate(5);
        return view('products.keranjang',compact('keranjangs'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('products.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
            'image' => 'required|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $fileName = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/images', $fileName);

        /*$data = Product::create([
            'image' => $image_path,
        ]);
        */
        //Product::create($request->all());

        $user = new Product;
        $user->name = $request->input('name');
        $user->detail = $request->input('detail');
        $user->image = $fileName;
        $user->save();
    
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Keranjang $product): View
    {
        return view('products.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product): View
    {
        return view('products.tambah',compact('product'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keranjang $product): RedirectResponse
    {
         request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $product->update($request->all());
    
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keranjang $keranjang): RedirectResponse
    {
        $keranjang->delete();
    
        return redirect()->route('keranjang')
                        ->with('success','Produk terbeli');
    }

    public function tambah(string $id)
    {
        return view('products.tambah', [ 
            //'title' => 'Edit',
            'method' => 'GET', 
            'action' => "tambah/$id",
            'product' => Product::find($id)
            ]);
    }

    public function tmbhkeranjang(Request $request, string $id): RedirectResponse
    {
        //Product::query()
        //->where('id','==',$request)
        //->each(function ($post) {
            $post = Product::find($id);

            $post->replicate()
            //$newPost->created_at = Keranjang::now();
            ->setTable('keranjangs')
            ->save();
        
    //});
    return redirect()->route('keranjang')
                        ->with('success','Product added');
}
}