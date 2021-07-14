<div class="el-image-container">
    @if($lists->isEmpty())
      <div class="empty">
          <div class="text-center">
              <span class="tips">暂无记录</span>
          </div>
      </div>
    @endif
    @foreach($lists as $resource)
        <div class="item checkMedia content-box" style="background-image: url('{{ $resource->remote_url }}')" data-json="{{ $resource }}">
            <div class="name">{{ $resource->file_name }}</div>
            <div class="mask">
                <div class="wi wi-right"></div>
            </div>
        </div>
    @endforeach
</div>

@if($lists->hasPages())
    <div class="material-list-paper">
        @if(!empty($cat_id))
            {{ $lists->appends(['cat_id'=>$cat_id,'module_name'=>$module_name])->links() }}
        @elseif(empty($cat_id))
            {{ $lists->appends(['module_name'=>$module_name])->links() }}
        @else
            {{ $lists->links() }}
        @endif
    </div>
@endif
