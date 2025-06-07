<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class ReportController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        return view('pages.report.index', compact('sponsors'));
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
            $filename = storage_path() . '/app/public/reports/' . 'Laporan ' . $sponsor->name . ' Tanggal ' . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y');
            $templateProcessor->saveAs($filename . '.docx');
            $path = storage_path() . '/app/public/reports';
            $files = File::allFiles($path);
            return [$sponsor->name, $files[0]->getFilename()];
        } catch (\Throwable $th) {
            BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
        }
    }
}
