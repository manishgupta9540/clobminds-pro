
@extends('layouts.admin')

@section('content')

 <style>

 .preview-images-zone {
    width: 100%;
    border: 2px dotted #666;
    min-height: 180px;
    /* display: flex; */
    padding: 5px 5px 0px 5px;
    position: relative;
    overflow:auto;
}
fieldset{
  text-align: center;
}
fieldset p{
  margin-top: 35px;
}
fieldset a{
    padding: .7rem 1.6rem;
    background: linear-gradient(180deg,#fff,#f9fafb);
    border: .1rem solid var(--p-border,#c4cdd5);
    box-shadow: 0 1px 0 0 rgba(22,29,37,.05);
    color:#666!important;
    margin-top:30px;
    border-radius: 3px;
}
.preview-images-zone > .preview-image:first-child {
    height: 185px;
    width: 185px;
    position: relative;
    margin-right: 5px;
}
.preview-images-zone > .preview-image {
    height: 90px;
    width: 90px;
    position: relative;
    margin-right: 5px;
    float: left;
    margin-bottom: 5px;
}
.preview-images-zone > .preview-image > .image-zone {
    width: 100%;
    height: 100%;
}
.preview-images-zone > .preview-image > .image-zone > img {
    width: 100%;
    height: 100%;
}
.preview-images-zone > .preview-image > .tools-edit-image {
    position: absolute;
    z-index: 100;
    color: #fff;
    bottom: 0;
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
    display: none;
}
.preview-images-zone > .preview-image > .image-cancel {
    font-size: 18px;
    position: absolute;
    top: 0;
    right: 0;
    font-weight: bold;
    margin-right: 10px;
    cursor: pointer;
    display: none;
    z-index: 100;
}
.preview-image:hover > .image-zone {
    cursor: move;
    opacity: .5;
}
.preview-image:hover > .tools-edit-image,
.preview-image:hover > .image-cancel {
    display: block;
}
.ui-sortable-helper {
    width: 90px !important;
    height: 90px !important;
}

.container {
    padding-top: 50px;
}

</style> 

<div class="clearfix"></div>
  
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">

          <div class="col-lg-10 col-xl-10">
            
            <h5 class="card-title">Import Customer </h5>
            <hr>

            <form action="{{route('/customer/import_save')}}" id="addProductForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
        
                  <div class="col-12 col-lg-8 col-xl-8">

                  <div class="product_module">
                    <div class="card">
                        <div class="card-header text-uppercase">Select File </div>
                        <div class="card-body">
                          <input type="file" class="form-control" name="import_file">
                          <!-- <div class="preview-images-zone">
                          
                            <fieldset class="form-group" style="">
                              <div class="preview-image preview-show-1" style="">
                                <div class="image-zone oldPre">
                                  <center><img style="height: 70px; width: 80px;" src="{{ asset('admin/assets/images/dummy-img.svg')}}"></center>
                                </div>
                                <div class="image-zone newPre" style="display: none;">
                                  <center><img style="height: auto;width: 100%;" id="pro-img-1" src="{{ asset('admin/assets/images/dummy-img.svg')}}" class="imagePreview"></center>
                                </div>
                                <p>can attach more than one</p></br>
                              </div>
                              <a href="javascript:void(0)" onclick="$('#uploadFile').click()">Upload Image</a>
                              <p id="errordiv"></p>
                              <input type="file" id="uploadFile" name="import_file" style="visibility: hidden;" class="form-control" multiple />
                              @if ($errors->has('uploadFile')) <p class="help-block error">{{ $errors->first('uploadFile') }}</p> @endif

                            </fieldset>
                      
                       
                            <div id="image_preview">
                            </div>
                          </div> -->
                        
                        <div class="form-footer">
                    <button type="submit" id="submit" class="btn btn-success"> SAVE</button>
                  </div>

                        </div>
                    </div>
                  </div>
                  

                  </div>
                </div>
                
            </form>
          </div>

      </div>
    
    </div>
</div>

    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>

  
@stack('scripts')  

@endsection