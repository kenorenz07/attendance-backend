<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Attendance Backoffice</title>
    <link rel="icon" href="{{ asset('/images/lenzy_logo_small.png') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.8.95/css/materialdesignicons.css"> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <style>
        td {
            padding: 0!important;
        }

        .present {
            background: #27b460;
        }

        .absent {
            background: #f53851;
        }
        .late{
            background: #ffc409;
        }
        .no-class {
            background: rgb(54, 54, 54);
        }

        .box {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            right: 5px;
            top: 3px;
            position: absolute;
        }
        .box-legend {
            width: 100px;
            position: relative;
        }
        .descriptions {
            position: relative;
        }
        .legends {
            top: 65px;
            position: absolute;
            right: 0;
        }
        .nothingness {
            border: 1px solid #dee2e6;
            padding-left: 4px;
        }
    </style>    
</head>


<body class="antialiased">
    <div class="descriptions">
        <div>
            <p class="text-center mb-0 mt-3">Summary of Attendance</p>
            <p class="text-center ">Duration : {{$class_detail->start_date}} to {{$class_detail->end_date}}</p>
            <p class="mb-0">Subject : {{$class_detail->subject->name}}</p>
            <p class="mb-0">Section : {{$class_detail->section->name}}</p>
            <p class="mb-0">Instructor : {{$class_detail->teacher->full_name}}</p>
            <p class="mb-0">Schedule : {{$class_detail->schedule->day}}, {{Carbon\Carbon::parse($class_detail->schedule->time_start)->format('g:i A')}} - {{Carbon\Carbon::parse($class_detail->schedule->time_end)->format('g:i A')}}</p>
            <p class="mb-0">Date printed : {{Carbon\Carbon::now()->format('m/d/Y')}}</p>
            @if ($user)
                <p class="mb-0">Printed by : {{$user}}</p>
            @endif
        </div>
        <div class="legends">
            <p class="mb-0"><strong> Legends </strong></p>
            <div class="box-legend">
                <p class="mb-0">Present : </p>
                <div class="box present"></div>
            </div>
            <div class="box-legend">
                <p class="mb-0">Absent : </p>
                <div class="box absent"></div>
            </div>
            <div class="box-legend">
                <p class="mb-0">Late : </p>
                <div class="box late"></div>
            </div>
            <div class="box-legend">
                <p class="mb-0">No class : </p>
                <div class="box no-class"></div>
            </div>
              
          </div>
    </div>

    <table class="table table-bordered mt-5 mb-0" style="table-layout:fixed;">
        <thead>
          <tr>
            <td style="width: 5%" class="text-center">Student Name</td>
            @foreach ($days as $day)
                <td style="width: 3%" class="text-center">{{Carbon\Carbon::parse($day)->format('d M')}}</td>
            @endforeach
          </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $key => $attendance)
                <tr>
                    <td style="width: 5%" >{{$key}}</td>
                    @foreach ($attendance as $att)
                        <td style="width: 3%" class="{{ $att }}"></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="nothingness">Nothing to follows</div>

      
</body>

</html>