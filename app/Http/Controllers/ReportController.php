<?php

namespace App\Http\Controllers;

use App\Models\Donation\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
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
        $path = public_path('reports/'); // Ganti 'your-directory' dengan direktori yang ingin di-scan
        $files = File::allFiles($path);
        foreach ($files as $file) {
            unlink(public_path('reports/') . $file->getFilename());
        }
        return redirect()->route('report.index')->with('success', 'Berhasil menghapus semua file');
    }
    public function download(Request $request)
    {
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(storage_path() . '\..\vendor\dompdf\dompdf');
        $sponsor = Sponsor::where('id', $request->sponsor_id)->first();
        $donations = $sponsor->donation()->with(['foods', 'heroes'])->whereBetween('take', [$request->startDate, $request->endDate])->get();
        foreach ($donations as $donation) {
            $templateProcessor = new TemplateProcessor('templates/default.docx');
            $volunteerName = $request["receiver-" . $donation->id];
            $volunteerRole = $request["role-" . $donation->id];
            $donationDate = \Carbon\Carbon::parse($donation->take)->isoFormat('dddd, D MMMM Y');
            $templateProcessor->setValue('sponsorName', $sponsor->name);
            $templateProcessor->setValue('volunteerName', $volunteerName);
            $templateProcessor->setValue('volunteerRole', $volunteerRole);
            $templateProcessor->setValue('donationDate', $donationDate);
            $templateProcessor->setValue('foodTotal', round($donation->foods->sum('weight') / 100) / 10);

            $heroes = $donation->heroes;
            $templateProcessor->cloneRow('heroesName', $heroes->count());
            for ($i = 1; $i < $heroes->count() + 1; $i++) {
                $templateProcessor->setValue("heroesName#$i", $heroes[$i - 1]->name);
                $templateProcessor->setValue("heroesFaculty#$i", $heroes[$i - 1]->faculty->name);
            }

            $foods = $donation->foods;
            $templateProcessor->cloneRow('foodName', $foods->count());
            for ($i = 1; $i < $foods->count() + 1; $i++) {
                $templateProcessor->setValue("foodName#$i", $foods[$i - 1]->name);
                $templateProcessor->setValue("foodWeight#$i", round($foods[$i - 1]->weight / 100) / 10);
            }
            $filename = public_path('reports/') . 'Laporan ' . $sponsor->name . ' Tanggal ' . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y');
            $templateProcessor->saveAs($filename . '.docx');
            // $reader = IOFactory::createReader('Word2007');
            // $phpWord = $reader->load($filename . '.docx'); // Load dokumen Word
            // $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            // $pdfWriter->save($filename . '.pdf');
        }
        $path = public_path('reports/'); // Ganti 'your-directory' dengan direktori yang ingin di-scan
        $files = File::allFiles($path);
        $reportFiles = [];
        foreach ($files as $file) {
            $reportFiles[] = $file->getFilename();
        }
        // return view('pages.report.download');
        return view('pages.report.download', compact('reportFiles'));
    }
}
