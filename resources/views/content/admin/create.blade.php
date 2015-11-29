<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Modal Tittle</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => route('admin_content_store')]) !!}
                    {!! Form::text('frm[content_title]', $entity['content_title'],['class' => 'form-control']) !!}
                {!! Form::submit('create new task',['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning"> Confirm</button>
            </div>
        </div>
    </div>
</div>
