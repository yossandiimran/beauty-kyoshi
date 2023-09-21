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
        // $no = 1;
        // $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            // $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            // $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            // $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $penjualan_detail = DB::table('penjualan_detail')
                                ->join('produk', 'penjualan_detail.id_produk', '=', 'produk.id_produk')
                                ->select('penjualan_detail.id_penjualan_detail', 'produk.nama_produk', 'penjualan_detail.harga_jual', 'penjualan_detail.jumlah', 'penjualan_detail.diskon', 'penjualan_detail.subtotal')
                                ->get();

             
            // $penjualan_detail = PenjualanDetail::orderBy('id_penjualan_detail')->get();
            // dd("$penjualan_detail");
            // dd("$total_penjualan");
            // return;
            $data = [];
            if(count($penjualan_detail) >= 0){
                $no = 1;
                foreach ($penjualan_detail as $detailItem) {
                     $data[] = [
                        'DT_RowIndex' => $no++,
                        'tanggal' => tanggal_indonesia($tanggal, false),
                         'nama_produk' => $detailItem->nama_produk,
                         'harga_jual' => $detailItem->harga_jual,
                         'jumlah' => $detailItem->jumlah,
                         'diskon' => $detailItem->diskon,
                         'subtotal' => $detailItem->subtotal
                     ];       
                 }
            }

            // dd($detail);
            // return;
            $pendapatan = $total_penjualan;
            $total_pendapatan += $pendapatan;

            // $row = array();
            // $row['DT_RowIndex'] = $no++;
            // $row['tanggal'] = tanggal_indonesia($tanggal, false);
            // $row['nama_produk'] = $data[1]['nama_produk'];
            // $detail['harga_jual'] = $detail[1]['harga_jual'];
            // $detail['jumlah'] = $detail[1]['jumlah'];
            // $detail['diskon'] = $detail[1]['diskon'];
            // $detail['subtotal'] = $detail[1]['subtotal'];
            // $row['penjualan'] = format_uang($total_penjualan);
            // // $row['pembelian'] = format_uang($total_pembelian);
            // // $row['pengeluaran'] = format_uang($total_pengeluaran);
            // $row['pendapatan'] = format_uang($pendapatan);

            // $data[] = $detail;
            // $data[] = $detail;

            // dd($data);
            // return;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'nama_produk' => '',
            'harga_jual' => '',
            'jumlah' => '',
            'diskon' => '',
            'subtotal' => '',
            // 'penjualan' => '',
            // 'pembelian' => '',
            // 'pengeluaran' => 'Total Pendapatan',
            // 'pendapatan' => format_uang($total_pendapatan),
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
