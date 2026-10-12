<ul class="action-button-list">
    <li>
    @if ($datapenerimaan->status=="ada")
        <a href="/penerimaan/{{ $datapenerimaan->kode_penerimaan }}/edit" class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Edit
            </span>
        </a>
        @elseif($dataizin->status=="c")
        <a href="/izincuti/{{ $dataizin->kode_izin }}/edit" class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Edit
            </span>
        </a>
        @elseif($dataizin->status=="d")
        <a href="/izincuti/{{ $dataizin->kode_izin }}/edit" class="btn btn-list text-primary">
            <span>
                <ion-icon name="create-outline"></ion-icon>
                Edit
            </span>
        </a>
        @endif
    </li>
    <li>
        <a href="#" id="deletebutton" class="btn btn-list text-danger" data-dismiss="modal" data-toggle="modal" data-target="#deleteConfirm">
            <span>
                <ion-icon name="trash-outline"></ion-icon>
                Delete
            </span>
        </a>
    </li>
</ul>

<script>
    $(function() {
        $("#deletebutton").click(function(e) {
            $("#hapuspenerimaan").attr('href', '/penerimaan/' + '{{ $datapenerimaan->kode_penerimaan }}/delete');
        });
    });

</script>
