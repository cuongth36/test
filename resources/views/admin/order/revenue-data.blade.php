@php
$new_start_date = date('m/d/Y', $start_date);
$new_end_date = date('m/d/Y', $end_date);
$data_total = []; 
$data_label = [];
$sub_total = 0;
for ($i= $start_date; $i <= $end_date; $i = strtotime('+1 day', $i)) { 
    if (!empty($total_price)) {
            $total = 0;
            foreach ($total_price as $key => $value) {
            $date = date_create($value->created_at);
            $date_formart = date_format($date,'Y-m-d');
        
            if(date('Y-m-d',$i) == $date_formart){
                $total = $value->total;
                $sub_total +=  $value->total;
            }
        }
        $data_total[] = $total;
    }
   $data_label[]= date('Y-m-d',$i); 
}
@endphp


<form action="" method="POST" id="form-action-statistical">
    @csrf
    <div class="form-statistical">
        <div class="start-date">
            <label for="">Date:</label>
            <input type="text" name="dateranger" id="date-ranger" value="{{ $new_start_date .'-'.$new_end_date }}">
            <input type="hidden" id="start-date" data-start-date="{{$new_start_date}}" value="{{$new_start_date}}" name="start-date">
            <input type="hidden" id="end-date"  data-end-date="{{$new_end_date}}" value="{{$new_end_date}}" name="end-date">
        </div>
        <input type="submit" name="submit-statistical" class="btn btn-primary statistical" value="Thống kê">
    </div>
</form>

<div class="statistical-month">
    
<p id="statistical-price"><span id="title">Doanh thu hiện có:</span> <span id="total-statistical">{{number_format($sub_total,0, '', '.')}}</span> đ</p>
</div>
<canvas id="myChart" width="100" height="100" name="canvas"></canvas>

@section('script')
<script>
    $(document).ready(function(){
        
        var ctx = document.getElementById('myChart').getContext('2d');
       
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:{!! json_encode($data_label) !!},
                datasets: [{
                    label: 'Doanh thu',
                    data:{!! json_encode($data_total) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            
                            beginAtZero: true
                        }
                    }]
                },
            }
        });

        
    });
</script>    
@endsection 