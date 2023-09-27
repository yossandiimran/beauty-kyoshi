<?php

namespace App\Http\Controllers;

// use App\Models\Pembelian;
// use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Member;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $pendapatan = 0;
        $total_pendapatan = 0;
        $hargaTotal = 0;
        $jumlahTotal = 0;
        $jumlahSub = 0;

        $penjualan_detail = PenjualanDetail::with('produk')
        ->whereDate('created_at', '>=', $awal)
        ->whereDate('created_at', '<=', $akhir)
        ->get();

        $data = [];
        if(count($penjualan_detail) >= 0){
            $no = 1;
            foreach ($penjualan_detail as $detailItem) {
                 $data[] = [
                    'DT_RowIndex' => $no++,
                    'tanggal' => tanggal_indonesia(date($detailItem->created_at), false),
                    'nama_produk' => $detailItem->produk->nama_produk,
                    'harga_jual' => format_uang($detailItem->harga_jual),
                    'jumlah' => $detailItem->jumlah,
                    'diskon' => $detailItem->diskon." %",
                    'subtotal' => format_uang($detailItem->subtotal)
                 ];  
                 
                 $hargaTotal =  $detailItem->harga_jual + $hargaTotal;
                 $jumlahTotal =  $detailItem->jumlah + $jumlahTotal;
                 $jumlahSub =  $detailItem->subtotal + $jumlahSub;
             }
        }

    $data[] = [
        'DT_RowIndex' => '',
        'tanggal' => '',
        'nama_produk' => '',
        'harga_jual' => format_uang($hargaTotal),
        'jumlah' => ($jumlahTotal),
        'diskon' => '',
        'subtotal' => format_uang($jumlahSub),
    ];

    return $data;

    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}
