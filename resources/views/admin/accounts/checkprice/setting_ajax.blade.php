{{-- <div class="table-responsive"> --}}
    @php
    $VIEW_ACCESS    = false;
    
    $VIEW_ACCESS    = Helper::can_access('Check Price Setting ','');
  @endphp
  @if ($VIEW_ACCESS)
      
 
    <table class="table table-bordered customerTable">
       <thead class="thead-light">
          <tr>
             {{-- <th>#</th> --}}
             <th scope="col" style="position:sticky; top:60px">Client Name</th>
             <th scope="col" style="position:sticky; top:60px">Show / Hide Status</th>
             <th scope="col" style="position:sticky; top:60px" width="10%">Action</th>
          </tr>
       </thead>
       <tbody>
          @if(count($items)>0)
             @foreach ($items as $key => $item)
             <?php $hide = Helper::check_price_show($item->id) ?>
                   <tr>
                      {{-- <td>{{$key+1}}</td> --}}
                      <td>{{ Helper::company_name($item->id)}} - {{Helper::user_name($item->id)}}</td>
                      <td>
                        @if($hide)
                            <span class="badge badge-dark" data-cus_id="{{ base64_encode($item->id)}}">Hidden</span>
                            <span class="d-none badge badge-info" data-cust_id="{{ base64_encode($item->id)}}">Show</span>
                        @else
                            <span class="d-none badge badge-dark" data-cus_id="{{ base64_encode($item->id)}}">Hidden</span>
                            <span class="badge badge-info" data-cust_id="{{ base64_encode($item->id)}}">Show</span>
                        @endif
                      </td>
                      <td>
                        @if ($hide)
                            <button class="btn btn-outline-info btn-md resume " type="button" data-customer_id="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye"></i></strong> Show</button>
                            <button class="btn btn-outline-dark btn-md hold d-none" type="button" data-customer="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye-slash"></i></strong> Hide</button>
                        @else
                            <button class="btn btn-outline-dark btn-md hold" type="button" data-customer="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye-slash"></i></strong> Hide</button>
                            <button class="btn btn-outline-info btn-md resume d-none" type="button" data-customer_id="{{ base64_encode($item->id) }}"><strong><i class="fas fa-eye"></i></strong> Show</button>
                        @endif
                    </td>
                   </tr> 
             @endforeach
          @else
             <tr class="text-center">
                   <td colspan="6">No Data Found</td>
             </tr>
          @endif    
       </tbody>
    </table>
 {{-- </div> --}}
 <div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >            
          {!! $items->render() !!}
      </div>
    </div>
 </div>
 @else
    <span>You have not any permission to action...</span> 
  @endif
 <script>
     $(document).ready(function(){
         
     });
 </script>