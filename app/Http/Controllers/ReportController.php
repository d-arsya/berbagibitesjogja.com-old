<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class ReportController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        return view('pages.report.index', compact('sponsors'));
    }
    public function downloadMonthly(string $code)
    {
        $record = DB::table('report_keys')->where('code', $code)->first();
        if (!$record) {
            return redirect('https://berbagibitesjogja.com');
        }
        $filePath = storage_path('app/public/monthly/' . $record->filename);
        return response()->download($filePath, $record->filename);
    }
    public function clean()
    {
        $path = storage_path() . '/app/public/reports/'; // Ganti 'your-directory' dengan direktori yang ingin di-scan
        $files = File::allFiles($path);
        foreach ($files as $file) {
            unlink(storage_path() . '/app/public/reports/' . $file->getFilename());
        }
        return redirect()->route('report.index')->with('success', 'Berhasil menghapus semua file');
    }
    public function download(Request $request)
    {
        $sponsor = Sponsor::where('id', $request->sponsor_id)->first();
        $donations = $sponsor->donation()->with(['foods', 'heroes'])->whereBetween('take', [$request->startDate, $request->endDate])->get();
        foreach ($donations as $donation) {
            $templateProcessor = new TemplateProcessor('templates/default.docx');
            $volunteerName = $request["receiver-" . $donation->id];
            $volunteerRole = $request["role-" . $donation->id];
            $donationDate = \Carbon\Carbon::parse($donation->take)->isoFormat('dddd, D MMMM Y');
            $templateProcessor->setValue('year', \Carbon\Carbon::now()->isoFormat('Y'));
            $templateProcessor->setValue('createdDate', \Carbon\Carbon::now()->isoFormat('D-M-Y'));
            $templateProcessor->setValue('sponsorName', $sponsor->name);
            $templateProcessor->setValue('donationDate', $donationDate);
            $templateProcessor->setValue('foodTotal', round($donation->foods->sum('weight') / 100) / 10);
            if ($donation->partner_id != null) {
                $heroes = $donation->partner->heroes;
            } else {
                $heroes = $donation->heroes;
            }
            $templateProcessor->setValue('totalHeroes', $heroes->sum('quantity'));
            $templateProcessor->cloneRow('heroesName', $heroes->count());
            for ($i = 1; $i < $heroes->count() + 1; $i++) {
                $templateProcessor->setValue("heroesName#$i", $heroes[$i - 1]->name);
                if ($heroes[$i - 1]->quantity > 1) {
                    $templateProcessor->setValue("heroesFaculty#$i", $heroes[$i - 1]->quantity . " Orang");
                } else {
                    $templateProcessor->setValue("heroesFaculty#$i", $heroes[$i - 1]->faculty->name . " (" . $heroes[$i - 1]->faculty->university->name . ")");
                }
            }

            $foods = $donation->foods;
            $templateProcessor->cloneRow('foodName', $foods->count());
            for ($i = 1; $i < $foods->count() + 1; $i++) {
                $templateProcessor->setValue("foodName#$i", $foods[$i - 1]->name);
                $templateProcessor->setValue("foodWeight#$i", round($foods[$i - 1]->weight / 100) / 10);
            }
            $filename = storage_path() . '/app/public/reports/' . 'Laporan ' . $sponsor->name . ' Tanggal ' . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y');
            $templateProcessor->saveAs($filename . '.docx');
        }
        $path = storage_path() . '/app/public/reports';
        $files = File::allFiles($path);
        $reportFiles = [];
        foreach ($files as $file) {
            $reportFiles[] = $file->getFilename();
        }
        return view('pages.report.download', compact('reportFiles'));
    }

    public static function createReport(Donation $donation)
    {
        try {
            $sponsor = $donation->sponsor;
            $templateProcessor = new TemplateProcessor(public_path() . '/templates/default.docx');
            $donationDate = \Carbon\Carbon::parse($donation->take)->isoFormat('dddd, D MMMM Y');
            $templateProcessor->setValue('year', \Carbon\Carbon::now()->isoFormat('Y'));
            $templateProcessor->setValue('createdDate', \Carbon\Carbon::now()->isoFormat('D-M-Y'));
            $templateProcessor->setValue('sponsorName', $sponsor->name);
            $templateProcessor->setValue('donationDate', $donationDate);
            $templateProcessor->setValue('foodTotal', round($donation->foods->sum('weight') / 100) / 10);
            if ($donation->partner_id != null) {
                $heroes = $donation->partner->heroes;
            } else {
                $heroes = $donation->heroes;
            }
            $templateProcessor->setValue('totalHeroes', $heroes->sum('quantity'));
            $templateProcessor->cloneRow('heroesName', $heroes->count());
            for ($i = 1; $i < $heroes->count() + 1; $i++) {
                $templateProcessor->setValue("heroesName#$i", $heroes[$i - 1]->name);
                if ($heroes[$i - 1]->quantity > 1) {
                    $templateProcessor->setValue("heroesFaculty#$i", $heroes[$i - 1]->quantity . " Orang");
                } else {
                    $templateProcessor->setValue("heroesFaculty#$i", $heroes[$i - 1]->faculty->name . " (" . $heroes[$i - 1]->faculty->university->name . ")");
                }
            }

            $foods = $donation->foods;
            $templateProcessor->cloneRow('foodName', $foods->count());
            for ($i = 1; $i < $foods->count() + 1; $i++) {
                $templateProcessor->setValue("foodName#$i", $foods[$i - 1]->name);
                $templateProcessor->setValue("foodWeight#$i", round($foods[$i - 1]->weight / 100) / 10);
            }
            $uniq = substr(bin2hex(random_bytes(2)), 0, 3);
            $filename = storage_path() . '/app/public/reports/' . 'Laporan ' . $sponsor->name . ' Tanggal ' . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y') . " $uniq";
            $templateProcessor->saveAs($filename . '.docx');
            $path = storage_path() . '/app/public/reports';
            $files = File::allFiles($path);
            return $files[0]->getFilename();
        } catch (\Throwable $th) {
            BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
        }
    }

    public static function createMonthlyReport($sponsor, $bulan)
    {
        $months = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $month = $months[$bulan];
        $donations = $sponsor->donation()->with(['heroes', 'foods'])->whereMonth('created_at', $bulan)->whereYear('created_at', now()->year)->get();

        $templateProcessor = new TemplateProcessor(public_path() . '/templates/monthly.docx');
        $templateProcessor->setValue('monthYear', $month . " " . \Carbon\Carbon::now()->isoFormat('Y'));
        $templateProcessor->setValue('sponsorName', $sponsor->name);
        $templateProcessor->setValue('createdDate', \Carbon\Carbon::now()->isoFormat('D-M-Y'));

        $templateProcessor->cloneBlock('block_donation', $donations->count(), true, true);

        $heroesTotal = 0;
        $foodsTotal = 0;

        foreach ($donations as $index => $donation) {
            $i = $index + 1; // block index

            $donationDate = Carbon::parse($donation->take)->isoFormat('dddd, D MMMM Y');
            $templateProcessor->setValue("donationDate#{$i}", $donationDate);

            // HEROES
            if ($donation->partner_id != null) {
                $heroes = $donation->partner->heroes;
            } else {
                $heroes = $donation->heroes;
            }
            $templateProcessor->setValue("totalHeroes#{$i}", $heroes->sum('quantity'));

            // clone row heroesName dalam setiap block
            $templateProcessor->cloneRow("heroesName#{$i}", $heroes->count());
            foreach ($heroes as $hIndex => $hero) {
                $h = $hIndex + 1;
                $templateProcessor->setValue("heroesName#{$i}#{$h}", $hero->name);
                if ($hero->quantity > 1) {
                    $templateProcessor->setValue("heroesFaculty#{$i}#{$h}", $hero->quantity . " Orang");
                } else {
                    $templateProcessor->setValue("heroesFaculty#{$i}#{$h}", $hero->faculty->name . " (" . $hero->faculty->university->name . ")");
                }
            }

            // FOODS
            $foods = $donation->foods;
            $heroesTotal += $heroes->sum('quantity');
            $foodsTotal += $foods->sum('weight');
            $templateProcessor->setValue("foodTotal#{$i}", round($foods->sum('weight') / 100) / 10);
            $templateProcessor->cloneRow("foodName#{$i}", $foods->count());
            foreach ($foods as $fIndex => $food) {
                $f = $fIndex + 1;
                $templateProcessor->setValue("foodName#{$i}#{$f}", $food->name);
                $templateProcessor->setValue("foodWeight#{$i}#{$f}", round($food->weight / 100) / 10);
            }
        }
        $templateProcessor->setValue("foodsTotal", round($foodsTotal / 100) / 10);
        $templateProcessor->setValue("heroesTotal", $heroesTotal);
        $uniq = substr(bin2hex(random_bytes(2)), 0, 3);
        $filename = storage_path() . '/app/public/monthly/' . 'Laporan ' . $sponsor->name . ' Bulan ' . $month . " $uniq";
        $templateProcessor->saveAs($filename . '.docx');
        $path = storage_path() . '/app/public/monthly';
        $files = File::allFiles($path);
        return $files[0]->getFilename();
    }

    public function getDonations(Sponsor $sponsor, $start, $end)
    {
        $donations = $sponsor->donation()->with(['foods', 'heroes'])->whereBetween('take', [$start, $end])->get();
        $totalWeight = 0;
        $totalFood = 0;
        $totalHero = 0;
        $totalAction = $donations->count();
        foreach ($donations as $item) {
            $total = $item->foods->sum('weight');
            $item->foodWeight = $total;
            $totalWeight += $total;
            $total = $item->foods->count();
            $item->foodQuantity += $total;
            $totalFood += $total;
            $total = $item->heroes->sum('quantity');
            $item->heroQuantity += $total;
            $totalHero += $total;
        }
        return response()->json(compact('totalWeight', 'totalFood', 'totalHero', 'totalAction', 'donations'));
    }
}
