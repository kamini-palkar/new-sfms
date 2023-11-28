<!DOCTYPE html>
<html>
<head>

</head>
<body>
<h5>{{ $title }}</h5>
   <p>Hello,</p>
    <p>{{ $body }}</p>


    <table border="1" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Sr No</th>
            <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">File Name</th>
        </tr>
        @foreach($filesForMail as $key => $file)
            <tr>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">{{ $key + 1 }}</td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">{{ $file->getClientOriginalName() }}</td>
            </tr>
        @endforeach
    </table>























    <p>Thank you.</p>

    <span>Regards,</span><br>
    <span></html>{{$regardsName}}
</span><br>
</body>