                <li class="{{ $i & 1 ? '' : 'timeline-inverted' }}">
                    <div class="timeline-badge">{{ $i}}</div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <span class="vertical-date pull-right"> {{Carbon::parse($event->Timestamp)->format('jS M, Y') }} <br/> 
                                    <small>
                                        {{Carbon::parse($event->Timestamp)->format('h:i A') }}
                                    </small> 
                                </span>

                                <h2>{{ $event->EventDescription or "" }}</h2>
                            </div>
                            <div class="timeline-body">
                                <p>
                                    {{ $event->Address->City or "" }}
                                    {{ $event->Address->CountryCode or "" }}
                                    
                                </p>
                                <p class="status_exception">
                                    {{ $event->StatusExceptionDescription or "" }}
                                </p>
                        </div>
                    </div>
                </li>