<?php

namespace App\Http\Controllers;

use App\Call;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class CallController extends Controller
{

    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        $location = sys_get_temp_dir() . '/' . $file->getFilename();
        $reader = \PHPExcel_IOFactory::load($location);

        $sheet = $reader->getAllSheets()[0];

        $totalRows = $sheet->getHighestRow();

        $data = [];

        for ($i = 2; $i <= $totalRows; $i++) {
            $entry = [
                'user' => trim($sheet->getCellByColumnAndRow(0, $i)->getValue()),
                'client' => trim($sheet->getCellByColumnAndRow(1, $i)->getValue()),
                'client_type' => trim($sheet->getCellByColumnAndRow(2, $i)->getValue()),
                'duration' => trim($sheet->getCellByColumnAndRow(4, $i)->getValue()),
                'type_of_call' => trim($sheet->getCellByColumnAndRow(5, $i)->getValue()),
                'external_call_score' => trim($sheet->getCellByColumnAndRow(6, $i)->getValue()),
                'date' => trim($sheet->getCellByColumnAndRow(3, $i)->getValue())
            ];
            $data[] = $entry;
        }

        Call::query()->insert($data);
        return true;
    }

    public function getAllUsers()
    {
        return Call::query()->select('user')->distinct('user')->get();
    }

    public function lastFiveCalls(Request $request)
    {
        $userName = $request->get('user_name');
        return Call::query()->where('user', $userName)->orderBy('date', 'DESC')->limit(5)->get();
    }

    public function averageUserScore(Request $request)
    {
        $totalAverage = Call::query()->where('user', $request->get('user_name'))->average('external_call_score');
        $data['total_score'] = $totalAverage;

        if ($request->get('type') == 'week')
            $minDate = Carbon::now()->subWeek();
        else
            $minDate = Carbon::now()->subMonth();

        $data['scores'] = Call::query()
            ->where('user', $request->get('user_name'))
            ->where('date', '>', $minDate)
            ->get();

        return $data;
    }

}
