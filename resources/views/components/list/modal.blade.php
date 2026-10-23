@props(['name' => 'الرئيسية' , 'save_button' => true ])


<div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
        <div class="modal-content" id="modalContent">
            <div class="modal-header">
                <h2>{{$name}}</h2>
                <button type="button" class="close btn-close-modal" id="close_model_x" data-dismiss="modal" aria-label="Close" style="cursor: pointer; background: transparent; border: none; font-size: 26px; line-height: 1; opacity: 0.7; color: #151515; padding: 4px 8px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-3" id="form-load">

            </div>
            <!--end::Modal body-->
            <div class="modal-footer">
                @if($save_button)
                    <div class=" ">
                        <input  form="form" value="حفظ" type="submit" id="submit" class="btn btn-primary " style="width: 100px">
                    </div>
                @endif
                <div class=" ">
                    <input type="button" class="btn btn-light me-3 " id="close_model" style="width: 100px" value="غلق">
                </div>
            </div>
        </div>

    </div>
</div>
