<?php

namespace App\Http\Controllers;

//import Facades Storage untuk menghapus gambar
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    //untuk melihat semua
    public function index()
    {
        //buat lihat semua
        $products = Product::all();

        return Response()->json(
            [
                "Message"=> "Berhasil",
                "code"=> 200,
                "data"=> $products
            ]);
    }

    //menampilkan salah satu data
    public function show($id)
    {
        $products = Product::find($id);
        return Response()->json(
            [
                "Message" => "Berhasil",
                "code" => 200,
                "data" => $products
            ]
        );
    }

    //menambah data
    public function add(Request $request)
    {
        // $waktu = date("Y-m-d", strtotime($item->date_begin));
        $data = $request->validate([
            "nama"=> "required",
            "deskripsi"=> "required",
            "foto"=> "image|max:2048",
            "modal"=> "required|numeric",
            "jual" => "required|numeric"
        ]);

        if($request->hasFile('foto'))
        {
            $image = $request->file('foto');
            // $imageName = time().'-'.$image->getClientOriginalExtension();
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'),$imageName);
            $data['foto'] = $imageName;
        }
        $products = Product::create($data);
        return Response()->json([
            "Message" => "Berhasil",
            "code" => 200,
            "data" => $products
        ]);
    }

    //edit data
    public function update(Request $request, $id){
        $data = $request->validate([
            //required artinya harus diisi
            "nama" => "required",
            "desc" => "required ",
            // max:2048 artinya max 2mb
            "foto" => "image|max:2048",
            "harga" => "required|numeric"
        ]);

        if ($request->hasFile('foto')) {
            //artinya jika kita punya foto
            //kita akan membuat variabel image
            $image  = $request->file('foto');
            //membuat nama foto
            $imageName = time() . '-' . $image->getClientOriginalExtension();
            //menyimpan file foto di public dan membuaat folder images
            $image->move(public_path('images'), $imageName);
            //buat masukin nama file foto
            $data['foto'] = $imageName;
        }
        //cari data
        $products = Product::find($data);

        //lalu di update
        Product::where('id',$id)->update($data);

        return Response()->json([
            "Message" => "Berhasil",
            "code" => 200,
            "data" => $products
        ]);
    }
    //edit santri coding
    public function updatesantri(Request $request, $id)
    {
        //melakukan validasi
        $data = $request->validate([
            "nama" => "required",
            "deskripsi" => "required",
            "foto" => "image|max:2048",
            "modal" => "required|numeric",
            "jual" => "required|numeric"
        ]);

        $post = Product::find($id);
        $post->update([
            "nama"=> $request->getParameter['nama'],
            "deskripsi"=> $request->getParameter['deskripsi'],
            "foto"=> $request->getParameter['foto'],
            "modal"=> $request->getParameter['modal'],
            "jual"=> $request->getParameter['jual']
        ]);
        //return Response()->json(["Message"=> "Edit Berhasil", "code"=> 200, "data"=> $post]);
    }

    //hapus data
    public function delete($id){
        $products = Product::find($id);
        $products->delete();

        return Response()->json([
            "Message" => "Berhasil",
            "code" => 200,
            "data" => $products
        ]);
    }
}
