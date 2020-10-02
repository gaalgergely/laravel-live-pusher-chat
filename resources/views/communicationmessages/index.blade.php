<div class="communicationmessage-wrapper">
    <ul class="communicationmessages">

        @foreach($communicationmessages as $communicationmessage)
        <li class="communicationmessage clearfix">
            <div class="{{ $communicationmessage->sender === auth()->user()->id ? 'messagesent' : 'messagereceived' }}">
                <p>{{ $communicationmessage->communicationmessage }}</p>
                <p class="messagedata">{{ $communicationmessage->created_at }}</p>
            </div>
        </li>
        @endforeach
    </ul>
</div>
