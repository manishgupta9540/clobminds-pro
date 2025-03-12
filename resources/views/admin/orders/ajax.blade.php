<div class="table-responsive">
    <table class="table " id="">
      <thead>
        <tr>
          <th scope="col"><input class="form-check-input tab" type="checkbox" value="" id="defaultCheck1tab"></th>
          <th scope="col"> Order ID </th>
          <th scope="col"> Customer </th>
          <th scope="col"> Date </th>
          <th scope="col"> Total </th>
          <th scope="col"> Payment</th>
          
          <th scope="col"> Items </th>
          <th scope="col"> Actions </th>
          
        </tr>
      </thead>
      <tbody id="">
        
        @if(count($orders) > 0 )

        @foreach($orders as $item)
        <?php
                $totalAmount =0;
                $userName = Common:: get_single_col('users','id',$item->customer_id,'first_name');
                $totalItems=Common::num_rows_count('order_items','order_id',$item->id);
                
                $currency = '$';
                $totalAmount = number_format(round(($item->total_amount + $item->delivery_charges +  $item->tax_amount),2),2);
                    
                switch ($item->customer_id) 
                {
                  case $userName == '':
                  $name = Common:: get_single_col('addresses','id',$item->customer_ship_address_id,'first_name').' '.Common:: get_single_col('addresses','id',$item->customer_ship_address_id,'last_name');
                  break;
                  default:
                  $name = Common:: get_single_col('users','id',$item->customer_id,'first_name').' '.Common:: get_single_col('users','id',$item->customer_id,'last_name');
                }

                $itm= ($totalItems == 1)?"item":"items";
                
                ?>
                <tr @if($item->customer_order_status_id == 2) class="cancel_orders" @endif><th scope="row"> <input class="form-check-input tab" type="checkbox" value="" id="defaultCheck1tab"></th>
                <td><a href="{{route('/admin/order/detail',['order_id'=>base64_encode($item->id)])}}">#{{$item->id}}</a></td>
                <td>{{$name}}</td>
                <td>{{ Carbon\Carbon::parse($item->created_at)->format('d M Y')}}</td>
                <td>{{'$'.$totalAmount}}</td>
                <td>
                    @if($item->payment_status == 0)
                    <span class=" _21Z9T -EFlq" style="background:#F0E5E3">Pending</span>
                    @else
                    <span class="_21Z9T -EFlq" style="background:#a2fda6">Paid</span>
                    @endif
                </td>
                
                {{-- <td>
                  <span class="_21Z9T i4fQI _33uWB">
                  <span class="-EFlq">                    
                  </span>&nbsp;Unfulfilled</span>
                </td> --}}
                <td>{{$totalItems.' '.$itm}}</td>
                <td>
                    <a href="{{route('/admin/order/detail',['order_id'=>base64_encode($item->id)])}}">View</a>
                </td>
               
              </tr>

              @endforeach

              @else
              <tr><td class="text-center" colspan="11">No record found!</td></tr>

            @endif

      </tbody>
    </table>
    
    <div class="col-md-12 orders">
      {!! $orders->render() !!}
    </div>
</div>