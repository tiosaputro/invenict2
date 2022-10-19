<table style ="width:100%">
    <thead>
    <tr>
            <th colspan="12" style="text-align:center; font-weight: bold; font-size: 14px;"> Daftar Peripheral ICT</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align:center; font-weight: bold; font-size: 12px;"> {{date('d M Y')}}</th>
        </tr>
        <tr>
            <br>
        </tr>
    <tr>
        <th style="text-align:left; font-weight: bold;">No.</th>
        <th style="text-align:left; font-weight: bold;">Peripheral</th>
        <th style="text-align:left; font-weight: bold;">Items</th>
        <th style="text-align:left; font-weight: bold;">Merk</th>
        <th style="text-align:left; font-weight: bold;">Type</th>
    </tr> 
    </thead>
    <tbody>
    @php $no=1 @endphp
    @foreach($Master as $s)
        <tr>
            <td style="text-align:left;" width="5"  height="5">{{$no++}}</td>
            <td style="text-align:left;" width="15" height="10">{{ $s->invent_code }}</td>
            <td style="text-align:left;" width="20" height="10">{{ $s->invent_desc }}</td>
            <td style="text-align:left;" width="20" height="15">{{ $s->invent_brand}}</td>
            <td style="text-align:left;" width="20" height="15">{{ $s->invent_type}}</td>
        </tr>
    @endforeach
    </tbody>
</table>