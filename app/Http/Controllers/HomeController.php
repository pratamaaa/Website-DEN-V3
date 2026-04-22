<?php

namespace App\Http\Controllers;

// use App\Helpers\Gudangfungsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Response;

class HomeController extends Controller
{
    public function menu()
    {
        $data['menu'] = DB::table('menu_utama')->orderBy('urutan');

        return view('depan.menu', $data);
    }

    public function index()
    {
        $homeData = Cache::remember('frontend:home:index', 600, function () {
            return [
                'sliders' => DB::table('image_slider')
                    ->where('is_active', 'yes')
                    ->orderBy('created_at', 'desc')
                    ->get(),

                'berita' => DB::table('berita')
                    ->where('id_status_berita', '2')
                    ->orderBy('tanggal_publikasi', 'desc')
                    ->limit(6)
                    ->get(),

                'ruedp' => DB::table('ruedp')
                    ->orderBy('urutan', 'asc')
                    ->get(),

                'rued' => DB::table('ruedp')
                    ->orderBy('urutan', 'asc')
                    ->first(),

                'prokumden' => DB::table('publikasi')
                    ->where('id_publikasi_kategori', '1')
                    ->orderBy('tanggal_publikasi', 'DESC')
                    ->get(),

                'layananpublik' => DB::table('layanan_publik')
                    ->orderBy('urutan', 'asc')
                    ->get(),

                'video' => DB::table('galerivideo')
                    ->orderBy('tanggal_publikasi', 'desc')
                    ->first(),

                'infografis' => DB::table('infografis')
                    ->orderBy('tanggal_publikasi', 'desc')
                    ->limit(6)
                    ->get(),

                'ruedpStatus' => \App\Models\RuedpStatus::withCount('provinsi')
                    ->orderBy('urutan')
                    ->get(),

                'ruedpMapData' => \App\Models\RuedpProvinsi::with('status')
                    ->get()
                    ->map(function ($p) {
                        return [
                            'kode' => strtoupper($p->nama_provinsi),
                            'nama' => $p->nama_provinsi,
                            'status' => $p->status->nama_status ?? '-',
                            'warna' => $p->status->warna ?? '#cccccc',
                            'nomor_perda' => $p->nomor_perda ?? '-',
                            'tanggal_update' => $p->tanggal_update
                                ? \Carbon\Carbon::parse($p->tanggal_update)->isoFormat('D MMMM YYYY')
                                : '-',
                            'keterangan' => $p->keterangan ?? '-',
                        ];
                    }),
            ];
        });

        return view('depan.home', $homeData);
    }

    public function profilden($slug)
    {
        $data['profil'] = DB::table('profil')->where('slug', $slug)->first();

        if ($data['profil']->is_page == 'no') {
            return view('depan.profilden', $data);
        } else {
            if (View::exists('depan.'.$data['profil']->nama_page)) {
                return view('depan.'.$data['profil']->nama_page, $data);
            } else {
                $data['namapage'] = $data['profil']->nama_page;

                return view('depan.pagenotfound', $data);
            }
        }
    }

    public function profilden_detail(Request $req)
    {
        $id_organisasiden = $req->get('id');

        $data['den'] = DB::table('organisasi_den')->where('id_organisasiden', $id_organisasiden)->first();

        $data['judulhalaman'] = 'Profil Anggota DEN';

        return view('depan.modalprofildendetail', $data);
    }

    public function berita()
    {
        $data['judulhalaman'] = '';
        $data['berita'] = DB::table('berita as b')
            ->join('berita_kategori as kat', 'b.id_berita_kategori', '=', 'kat.id_berita_kategori')
            ->orderBy('tanggal_publikasi', 'desc')->paginate(12);
        $data['infografis'] = DB::table('infografis')->orderBy('tanggal_publikasi', 'desc')->limit(6)->get();
        $data['kategori'] = DB::table('berita_kategori')->orderBy('kategori_berita', 'asc');
        $data['recentnews'] = DB::table('berita')->orderBy('tanggal_publikasi', 'desc')->limit(5)->get();
        $data['popularnews'] = DB::table('berita')->orderBy('hits', 'desc')->limit(5)->get();

        return view('depan.berita', $data);
    }

    public function berita_baca($slug)
    {
        $cacheKey = 'frontend:berita:detail:'.$slug;

        $berita = Cache::remember($cacheKey, 1800, function () use ($slug) {
            return DB::table('berita as b')
                ->join('berita_kategori as kat', 'b.id_berita_kategori', '=', 'kat.id_berita_kategori')
                ->where('slug', $slug)
                ->first();
        });

        abort_if(! $berita, 404);

        $data['judulhalaman'] = 'Baca Berita';
        $data['berita'] = $berita;

        $data['infografis'] = Cache::remember('sidebar:infografis', 1800, function () {
            return DB::table('infografis')
                ->orderBy('tanggal_publikasi', 'desc')
                ->limit(6)
                ->get();
        });

        $data['kategori'] = Cache::remember('sidebar:kategori', 3600, function () {
            return DB::table('berita_kategori')
                ->orderBy('kategori_berita', 'asc')
                ->get();
        });

        $data['recentnews'] = Cache::remember('sidebar:recentnews', 600, function () {
            return DB::table('berita')
                ->orderBy('tanggal_publikasi', 'desc')
                ->limit(5)
                ->get();
        });

        $data['popularnews'] = Cache::remember('sidebar:popularnews', 600, function () {
            return DB::table('berita')
                ->orderBy('hits', 'desc')
                ->limit(5)
                ->get();
        });

        // hits tetap realtime
        DB::table('berita')
            ->where('id_berita', $berita->id_berita)
            ->increment('hits');

        return view('depan.berita_baca', $data);
    }

    public function berita_kategori($slug)
    {
        $kategori = ucwords(str_replace('-', ' ', $slug));
        $data['judulhalaman'] = 'Kategori '.$kategori;
        $data['berita'] = DB::table('berita as b')
            ->join('berita_kategori as kat', 'b.id_berita_kategori', '=', 'kat.id_berita_kategori')
            ->where('kat.kategori_slug', '=', $slug)
            ->orderBy('tanggal_publikasi', 'desc')->paginate(12);
        $data['infografis'] = DB::table('infografis')->orderBy('tanggal_publikasi', 'desc')->limit(6)->get();
        $data['kategori'] = DB::table('berita_kategori')->orderBy('kategori_berita', 'asc');
        $data['recentnews'] = DB::table('berita')->orderBy('tanggal_publikasi', 'desc')->limit(5)->get();
        $data['popularnews'] = DB::table('berita')->orderBy('hits', 'desc')->limit(5)->get();

        return view('depan.berita', $data);
    }

    public function berita_cari(Request $req)
    {
        $keyword = $req->post('katakunci');

        $data['judulhalaman'] = 'Pencarian: '.$keyword;
        $data['berita'] = DB::table('berita as b')
            ->join('berita_kategori as kat', 'b.id_berita_kategori', '=', 'kat.id_berita_kategori')
            ->where('b.judul', 'like', '%'.$keyword.'%')
            ->orderBy('tanggal_publikasi', 'desc')->paginate(12);
        $data['infografis'] = DB::table('infografis')->orderBy('tanggal_publikasi', 'desc')->limit(6)->get();
        $data['kategori'] = DB::table('berita_kategori')->orderBy('kategori_berita', 'asc');
        $data['recentnews'] = DB::table('berita')->orderBy('tanggal_publikasi', 'desc')->limit(5)->get();
        $data['popularnews'] = DB::table('berita')->orderBy('hits', 'desc')->limit(5)->get();

        return view('depan.berita', $data);
    }

    public function bacadokumen(Request $req)
    {
        $id_publikasi = $req->get('id');
        $prokum = DB::table('publikasi')->where('id_publikasi', $id_publikasi)->first();

        $data['judulhalaman'] = $prokum->judul_publikasi;
        $data['prokum'] = $prokum;

        return view('depan.modalbacadokumen', $data);
    }

    public function pdfviewer(Request $req)
    {
        $id = $req->get('id');
        $cat = $req->get('cat');

        if ($cat == 'publikasi') {
            $datapublikasi = DB::table('publikasi')->where('id_publikasi', $id)->first();
            DB::table('publikasi')->where('id_publikasi', $id)->update(['hits' => $datapublikasi->hits + 1]);

            $data['judulhalaman'] = $datapublikasi->judul_publikasi;
            $data['namafolder'] = 'publikasi';
        } elseif ($cat == 'reformasi-birokrasi') {
            $datapublikasi = DB::table('rb')->where('id_rb', $id)->first();
            DB::table('rb')->where('id_rb', $id)->update(['hits' => $datapublikasi->hits + 1]);

            $data['judulhalaman'] = $datapublikasi->judul;
            $data['namafolder'] = 'rb';
        } elseif ($cat == 'infografis') {
            $datapublikasi = DB::table('infografis')->where('id_infografis', $id)->first();
            DB::table('infografis')->where('id_infografis', $id)->update(['hits' => $datapublikasi->hits + 1]);

            $data['judulhalaman'] = $datapublikasi->judul_infografis;
            $data['namafolder'] = 'infografis';
        }
        $data['data'] = $datapublikasi;

        return view('depan.modalpdfviewer', $data);
    }

    public function modalruedp(Request $req)
    {
        $id = $req->get('id');

        $data['ruedp'] = DB::table('ruedp')->where('id_ruedp', $id)->first();

        return view('depan.modalrued', $data);
    }

    public function video()
    {
        $data['judulhalaman'] = 'Video';
        $data['video'] = DB::table('galerivideo')->orderBy('created_at', 'desc')->paginate(6);

        return view('depan.video', $data);
    }

    public function video_cari(Request $req)
    {
        $keyword = $req->post('katakunci');

        $data['judulhalaman'] = 'Hasil Pencarian Video: '.$keyword;
        $data['video'] = DB::table('galerivideo')
            ->where('judul', 'like', '%'.$keyword.'%')
            ->orderBy('tanggal_publikasi', 'desc')->paginate(6);

        return view('depan.video', $data);
    }

    public function infografis()
    {
        $data['judulhalaman'] = 'Infografis';
        $data['infog'] = DB::table('infografis')->orderBy('created_at', 'desc')->paginate(6);

        return view('depan.infografis', $data);
    }

    public function infografis_cari(Request $req)
    {
        $keyword = $req->post('katakunci');

        $data['judulhalaman'] = 'Hasil Pencarian Infografis: '.$keyword;
        $data['infog'] = DB::table('infografis')
            ->where('judul_infografis', 'like', '%'.$keyword.'%')
            ->orderBy('tanggal_publikasi', 'desc')->paginate(6);

        return view('depan.infografis', $data);
    }

    public function reformasi_birokrasi($slug)
    {
        $data['kategorirb'] = DB::table('rb_kategori')->orderBy('urutan', 'asc');

        $kategori_rb = $slug;
        $kat_rb = DB::table('rb_kategori')->where('slug', $kategori_rb)->first();
        $id_katrb = $kat_rb->id_rbkategori;

        $data['slug'] = $kategori_rb;

        if ($id_katrb == '1') {           // BERITA RB
            $data['judulhalaman'] = 'Berita Reformasi Birokrasi';
            $data['berita'] = DB::table('berita')->where('id_berita_kategori', '2')->orderBy('tanggal_publikasi', 'desc')->paginate(4);

            return view('depan.rb_berita', $data);

        } else {
            $rbkat = DB::table('rb_kategori')->where('slug', $kategori_rb);

            if ($kat_rb->is_page == 'yes') {
                if ($rbkat->count() != 0) {
                    $data['judulhalaman'] = $rbkat->first()->nama_rbkategori;
                    $data['data'] = DB::table('rb')->where('id_rbkategori', $id_katrb)->paginate(4);

                    return view('depan.rb', $data);
                }
            } else {
                $data['judulhalaman'] = $rbkat->first()->nama_rbkategori;

                return view('depan.'.$slug, $data);
            }
        }
    }

    public function reformasi_birokrasi_newsread($slug)
    {
        $data['judulhalaman'] = 'Reformasi Birokrasi';
        $data['kategorirb'] = DB::table('rb_kategori')->orderBy('urutan', 'asc')->get();
        $data['berita'] = DB::table('berita')->where('slug', $slug)->first();

        return view('depan.reformasibirokrasi_baca', $data);
    }

    public function reformasi_birokrasi_cari(Request $req)
    {
        $keyword = $req->post('katakunci');
        $slug = $req->post('slug');

        $katpub = DB::table('rb_kategori')->where('slug', $slug);
        if ($katpub->count() != 0) {
            $id_katpub = $katpub->first()->id_rbkategori;
        } else {
            $id_katpub = '1';
        }

        $data['judulhalaman'] = 'Pencarian: '.$keyword;
        $data['data'] = DB::table('rb as ref')
            ->join('rb_kategori as kat', 'ref.id_rbkategori', '=', 'kat.id_rbkategori')
            ->where('ref.id_rbkategori', $id_katpub)
            ->where('ref.judul', 'like', '%'.$keyword.'%')
            ->paginate(6);

        $data['kategorirb'] = DB::table('rb_kategori')->orderBy('urutan', 'asc')->get();
        $data['slug'] = $slug;

        return view('depan.rb_cari', $data);
    }

    public function publikasi($slug)
    {
        $kategori = DB::table('publikasi_kategori')->where('slug', $slug);

        if ($kategori->count() == 0) {
            $data['judulhalaman'] = '';
            $id_kategori = 0;
        } else {
            $data['judulhalaman'] = $kategori->first()->nama_kategori;
            $id_kategori = $kategori->first()->id_publikasi_kategori;
        }

        $data['data'] = DB::table('publikasi as pub')
            ->join('publikasi_kategori as kat', 'pub.id_publikasi_kategori', '=', 'kat.id_publikasi_kategori')
            ->where('pub.id_publikasi_kategori', $id_kategori)
            ->orderBy('pub.tanggal_publikasi', 'desc')
            ->paginate(6);
        $data['katpublikasi'] = DB::table('publikasi_kategori')->orderBy('urutan', 'asc');
        $data['slug'] = $slug;

        return view('depan.publikasi', $data);
    }

    public function publikasi_cari(Request $req)
    {
        $keyword = $req->post('katakunci');
        $slug = $req->post('slug');

        $katpub = DB::table('publikasi_kategori')->where('slug', $slug);
        if ($katpub->count() != 0) {
            $id_katpub = $katpub->first()->id_publikasi_kategori;
        } else {
            $id_katpub = '1';
        }

        $data['judulhalaman'] = 'Pencarian: '.$keyword;
        $data['data'] = DB::table('publikasi as pub')
            ->join('publikasi_kategori as kat', 'pub.id_publikasi_kategori', '=', 'kat.id_publikasi_kategori')
            ->where('pub.id_publikasi_kategori', $id_katpub)
            ->where('pub.judul_publikasi', 'like', '%'.$keyword.'%')
            ->paginate(6);
        $data['katpublikasi'] = DB::table('publikasi_kategori')->orderBy('urutan', 'asc');
        $data['slug'] = $slug;

        return view('depan.publikasi_cari', $data);
    }

    public function daftar_informasi_publik()
    {
        $data['judulhalaman'] = 'Daftar Informasi Publik';
        $data['dip'] = DB::table('daftar_informasi_publik')->orderBy('pic_satker', 'desc');

        return view('depan.daftar_informasi_publik', $data);
    }

    public function download(Request $req)
    {
        $id_rb = $req->get('num');

        $data = DB::table('rb')->where('id_rb', $id_rb);
        if ($data->count() != 0) {
            $namafile = public_path().'/uploads/rb/'.$data->first()->file;

            if (file_exists($namafile) == true) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($namafile));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: '.filesize($namafile));
                ob_clean();
                flush();
                readfile($namafile);
                exit;
            } else {
                echo "<script>alert('File path does not exist');history.back();</script>";
            }
        } else {
            echo 'Not found';
        }

    }

    public function download__(Request $req)
    {
        $id_rb = $req->get('num');

        $data = DB::table('rb')->where('id_rb', $id_rb);

        if ($data->count() != 0) {
            $namafile = public_path().'/uploads/rb/'.$data->first()->file;

            $headers = ['Content-Type: application/pdf'];

            return Response::download($namafile, $data->first()->file, $headers);
        } else {
            echo 'Not found';
        }
    }

    public function kontak()
    {
        $data['judulhalaman'] = 'Kontak Kami';
        $data['kontak'] = DB::table('identitas_organisasi')->orderBy('id_identitas_organisasi', 'asc')->limit(1);

        return view('depan.kontak', $data);
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function bengkel()
    {
        // Get data from Youtube
        $youtubeid = 'RhwW7U44Yr4';
        $url = "https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=$youtubeid&format=json";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($curl);

        echo $result;

        // $ytdata = Gudangfungsi::getDataFromYoutube($youtubeid);
        // // $data = json_decode($ytdata, true);
        // echo "Judul: ".$ytdata['title'];
    }
}
