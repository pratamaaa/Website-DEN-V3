@extends('layout.dapur.app')

@section('content')
<style>
    .table th{text-align: center;}
    .table td, .table th {padding: 7px 7px;}
    .ratakanan{text-align: right;}
    .ratatengah{text-align: center !important;}
</style>

<section class="pcoded-main-container">
    <div class="pcoded-content">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <nav class="navbar" style="margin-left:-10px !important;">
                            <span class="m-r-15"><h5>{{ $judulhalaman }}</h5></span>
                            <div class="nav-item nav-grid f-view">
                                {{-- <button type="button" id="btnFormTambah2" onclick="showFormadd()" class="btn waves-effect waves-light btn-primary btn-sm btn-icon m-0" data-toggle="tooltip" data-placement="top" title="Tambah">
                                    <i class="feather icon-plus-circle"></i> Tambah
                                </button> --}}
                            </div>
                        </nav>
                        <hr>

                        <div class="row">
                            <div class="col"><i class="icon-user icons"></i><span class="name">user</span></div>
                            <div class="col"><i class="icon-people icons"></i><span class="name">people</span></div>
                            <div class="col"><i class="icon-user-female icons"></i><span class="name">user-female</span></div>
                            <div class="col"><i class="icon-user-follow icons"></i><span class="name">user-follow</span></div>
                            <div class="col"><i class="icon-user-following icons"></i><span class="name">user-following</span></div>
                            <div class="col"><i class="icon-user-unfollow icons"></i><span class="name">user-unfollow</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-login icons"></i><span class="name">login</span></div>
                            <div class="col"><i class="icon-logout icons"></i><span class="name">logout</span></div>
                            <div class="col"><i class="icon-emotsmile icons"></i><span class="name">emotsmile</span></div>
                            <div class="col"><i class="icon-phone icons"></i><span class="name">phone</span></div>
                            <div class="col"><i class="icon-call-end icons"></i><span class="name">call-end</span></div>
                            <div class="col"><i class="icon-call-in icons"></i><span class="name">call-in</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-call-out icons"></i><span class="name">call-out</span></div>
                            <div class="col"><i class="icon-map icons"></i><span class="name">map</span></div>
                            <div class="col"><i class="icon-location-pin icons"></i><span class="name">location-pin</span></div>
                            <div class="col"><i class="icon-direction icons"></i><span class="name">direction</span></div>
                            <div class="col"><i class="icon-directions icons"></i><span class="name">directions</span></div>
                            <div class="col"><i class="icon-compass icons"></i><span class="name">compass</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-layers icons"></i><span class="name">layers</span></div>
                            <div class="col"><i class="icon-menu icons"></i><span class="name">menu</span></div>
                            <div class="col"><i class="icon-list icons"></i><span class="name">list</span></div>
                            <div class="col"><i class="icon-options-vertical icons"></i><span class="name">options-vertical</span></div>
                            <div class="col"><i class="icon-options icons"></i><span class="name">options</span></div>
                            <div class="col"><i class="icon-arrow-down icons"></i><span class="name">arrow-down</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-arrow-left icons"></i><span class="name">arrow-left</span></div>
                            <div class="col"><i class="icon-arrow-right icons"></i><span class="name">arrow-right</span></div>
                            <div class="col"><i class="icon-arrow-up icons"></i><span class="name">arrow-up</span></div>
                            <div class="col"><i class="icon-arrow-up-circle icons"></i><span class="name">arrow-up-circle</span></div>
                            <div class="col"><i class="icon-arrow-left-circle icons"></i><span class="name">arrow-left-circle</span></div>
                            <div class="col"><i class="icon-arrow-right-circle icons"></i><span class="name">arrow-right-circle</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-arrow-down-circle icons"></i><span class="name">arrow-down-circle</span></div>
                            <div class="col"><i class="icon-check icons"></i><span class="name">check</span></div>
                            <div class="col"><i class="icon-clock icons"></i><span class="name">clock</span></div>
                            <div class="col"><i class="icon-plus icons"></i><span class="name">plus</span></div>
                            <div class="col"><i class="icon-minus icons"></i><span class="name">minus</span></div>
                            <div class="col"><i class="icon-close icons"></i><span class="name">close</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-event icons"></i><span class="name">event</span></div>
                            <div class="col"><i class="icon-exclamation icons"></i><span class="name">exclamation</span></div>
                            <div class="col"><i class="icon-organization icons"></i><span class="name">organization</span></div>
                            <div class="col"><i class="icon-trophy icons"></i><span class="name">trophy</span></div>
                            <div class="col"><i class="icon-screen-smartphone icons"></i><span class="name">screen-smartphone</span></div>
                            <div class="col"><i class="icon-screen-desktop icons"></i><span class="name">screen-desktop</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-plane icons"></i><span class="name">plane</span></div>
                            <div class="col"><i class="icon-notebook icons"></i><span class="name">notebook</span></div>
                            <div class="col"><i class="icon-mustache icons"></i><span class="name">mustache</span></div>
                            <div class="col"><i class="icon-mouse icons"></i><span class="name">mouse</span></div>
                            <div class="col"><i class="icon-magnet icons"></i><span class="name">magnet</span></div>
                            <div class="col"><i class="icon-energy icons"></i><span class="name">energy</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-disc icons"></i><span class="name">disc</span></div>
                            <div class="col"><i class="icon-cursor icons"></i><span class="name">cursor</span></div>
                            <div class="col"><i class="icon-cursor-move icons"></i><span class="name">cursor-move</span></div>
                            <div class="col"><i class="icon-crop icons"></i><span class="name">crop</span></div>
                            <div class="col"><i class="icon-chemistry icons"></i><span class="name">chemistry</span></div>
                            <div class="col"><i class="icon-speedometer icons"></i><span class="name">speedometer</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-shield icons"></i><span class="name">shield</span></div>
                            <div class="col"><i class="icon-screen-tablet icons"></i><span class="name">screen-tablet</span></div>
                            <div class="col"><i class="icon-magic-wand icons"></i><span class="name">magic-wand</span></div>
                            <div class="col"><i class="icon-hourglass icons"></i><span class="name">hourglass</span></div>
                            <div class="col"><i class="icon-graduation icons"></i><span class="name">graduation</span></div>
                            <div class="col"><i class="icon-ghost icons"></i><span class="name">ghost</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-game-controller icons"></i><span class="name">game-controller</span></div>
                            <div class="col"><i class="icon-fire icons"></i><span class="name">fire</span></div>
                            <div class="col"><i class="icon-eyeglass icons"></i><span class="name">eyeglass</span></div>
                            <div class="col"><i class="icon-envelope-open icons"></i><span class="name">envelope-open</span></div>
                            <div class="col"><i class="icon-envelope-letter icons"></i><span class="name">envelope-letter</span></div>
                            <div class="col"><i class="icon-bell icons"></i><span class="name">bell</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-badge icons"></i><span class="name">badge</span></div>
                            <div class="col"><i class="icon-anchor icons"></i><span class="name">anchor</span></div>
                            <div class="col"><i class="icon-wallet icons"></i><span class="name">wallet</span></div>
                            <div class="col"><i class="icon-vector icons"></i><span class="name">vector</span></div>
                            <div class="col"><i class="icon-speech icons"></i><span class="name">speech</span></div>
                            <div class="col"><i class="icon-puzzle icons"></i><span class="name">puzzle</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-printer icons"></i><span class="name">printer</span></div>
                            <div class="col"><i class="icon-present icons"></i><span class="name">present</span></div>
                            <div class="col"><i class="icon-playlist icons"></i><span class="name">playlist</span></div>
                            <div class="col"><i class="icon-pin icons"></i><span class="name">pin</span></div>
                            <div class="col"><i class="icon-picture icons"></i><span class="name">picture</span></div>
                            <div class="col"><i class="icon-handbag icons"></i><span class="name">handbag</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-globe-alt icons"></i><span class="name">globe-alt</span></div>
                            <div class="col"><i class="icon-globe icons"></i><span class="name">globe</span></div>
                            <div class="col"><i class="icon-folder-alt icons"></i><span class="name">folder-alt</span></div>
                            <div class="col"><i class="icon-folder icons"></i><span class="name">folder</span></div>
                            <div class="col"><i class="icon-film icons"></i><span class="name">film</span></div>
                            <div class="col"><i class="icon-feed icons"></i><span class="name">feed</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-drop icons"></i><span class="name">drop</span></div>
                            <div class="col"><i class="icon-drawer icons"></i><span class="name">drawer</span></div>
                            <div class="col"><i class="icon-docs icons"></i><span class="name">docs</span></div>
                            <div class="col"><i class="icon-doc icons"></i><span class="name">doc</span></div>
                            <div class="col"><i class="icon-diamond icons"></i><span class="name">diamond</span></div>
                            <div class="col"><i class="icon-cup icons"></i><span class="name">cup</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-calculator icons"></i><span class="name">calculator</span></div>
                            <div class="col"><i class="icon-bubbles icons"></i><span class="name">bubbles</span></div>
                            <div class="col"><i class="icon-briefcase icons"></i><span class="name">briefcase</span></div>
                            <div class="col"><i class="icon-book-open icons"></i><span class="name">book-open</span></div>
                            <div class="col"><i class="icon-basket-loaded icons"></i><span class="name">basket-loaded</span></div>
                            <div class="col"><i class="icon-basket icons"></i><span class="name">basket</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-bag icons"></i><span class="name">bag</span></div>
                            <div class="col"><i class="icon-action-undo icons"></i><span class="name">action-undo</span></div>
                            <div class="col"><i class="icon-action-redo icons"></i><span class="name">action-redo</span></div>
                            <div class="col"><i class="icon-wrench icons"></i><span class="name">wrench</span></div>
                            <div class="col"><i class="icon-umbrella icons"></i><span class="name">umbrella</span></div>
                            <div class="col"><i class="icon-trash icons"></i><span class="name">trash</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-tag icons"></i><span class="name">tag</span></div>
                            <div class="col"><i class="icon-support icons"></i><span class="name">support</span></div>
                            <div class="col"><i class="icon-frame icons"></i><span class="name">frame</span></div>
                            <div class="col"><i class="icon-size-fullscreen icons"></i><span class="name">size-fullscreen</span></div>
                            <div class="col"><i class="icon-size-actual icons"></i><span class="name">size-actual</span></div>
                            <div class="col"><i class="icon-shuffle icons"></i><span class="name">shuffle</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-share-alt icons"></i><span class="name">share-alt</span></div>
                            <div class="col"><i class="icon-share icons"></i><span class="name">share</span></div>
                            <div class="col"><i class="icon-rocket icons"></i><span class="name">rocket</span></div>
                            <div class="col"><i class="icon-question icons"></i><span class="name">question</span></div>
                            <div class="col"><i class="icon-pie-chart icons"></i><span class="name">pie-chart</span></div>
                            <div class="col"><i class="icon-pencil icons"></i><span class="name">pencil</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-note icons"></i><span class="name">note</span></div>
                            <div class="col"><i class="icon-loop icons"></i><span class="name">loop</span></div>
                            <div class="col"><i class="icon-home icons"></i><span class="name">home</span></div>
                            <div class="col"><i class="icon-grid icons"></i><span class="name">grid</span></div>
                            <div class="col"><i class="icon-graph icons"></i><span class="name">graph</span></div>
                            <div class="col"><i class="icon-microphone icons"></i><span class="name">microphone</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-music-tone-alt icons"></i><span class="name">music-tone-alt</span></div>
                            <div class="col"><i class="icon-music-tone icons"></i><span class="name">music-tone</span></div>
                            <div class="col"><i class="icon-earphones-alt icons"></i><span class="name">earphones-alt</span></div>
                            <div class="col"><i class="icon-earphones icons"></i><span class="name">earphones</span></div>
                            <div class="col"><i class="icon-equalizer icons"></i><span class="name">equalizer</span></div>
                            <div class="col"><i class="icon-like icons"></i><span class="name">like</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-dislike icons"></i><span class="name">dislike</span></div>
                            <div class="col"><i class="icon-control-start icons"></i><span class="name">control-start</span></div>
                            <div class="col"><i class="icon-control-rewind icons"></i><span class="name">control-rewind</span></div>
                            <div class="col"><i class="icon-control-play icons"></i><span class="name">control-play</span></div>
                            <div class="col"><i class="icon-control-pause icons"></i><span class="name">control-pause</span></div>
                            <div class="col"><i class="icon-control-forward icons"></i><span class="name">control-forward</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-control-end icons"></i><span class="name">control-end</span></div>
                            <div class="col"><i class="icon-volume-1 icons"></i><span class="name">volume-1</span></div>
                            <div class="col"><i class="icon-volume-2 icons"></i><span class="name">volume-2</span></div>
                            <div class="col"><i class="icon-volume-off icons"></i><span class="name">volume-off</span></div>
                            <div class="col"><i class="icon-calendar icons"></i><span class="name">calendar</span></div>
                            <div class="col"><i class="icon-bulb icons"></i><span class="name">bulb</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-chart icons"></i><span class="name">chart</span></div>
                            <div class="col"><i class="icon-ban icons"></i><span class="name">ban</span></div>
                            <div class="col"><i class="icon-bubble icons"></i><span class="name">bubble</span></div>
                            <div class="col"><i class="icon-camrecorder icons"></i><span class="name">camrecorder</span></div>
                            <div class="col"><i class="icon-camera icons"></i><span class="name">camera</span></div>
                            <div class="col"><i class="icon-cloud-download icons"></i><span class="name">cloud-download</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-cloud-upload icons"></i><span class="name">cloud-upload</span></div>
                            <div class="col"><i class="icon-envelope icons"></i><span class="name">envelope</span></div>
                            <div class="col"><i class="icon-eye icons"></i><span class="name">eye</span></div>
                            <div class="col"><i class="icon-flag icons"></i><span class="name">flag</span></div>
                            <div class="col"><i class="icon-heart icons"></i><span class="name">heart</span></div>
                            <div class="col"><i class="icon-info icons"></i><span class="name">info</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-key icons"></i><span class="name">key</span></div>
                            <div class="col"><i class="icon-link icons"></i><span class="name">link</span></div>
                            <div class="col"><i class="icon-lock icons"></i><span class="name">lock</span></div>
                            <div class="col"><i class="icon-lock-ope icons"></i><span class="name">lock-ope</span></div>
                            <div class="col"><i class="icon-magnifier icons"></i><span class="name">magnifier</span></div>
                            <div class="col"><i class="icon-magnifier-add icons"></i><span class="name">magnifier-add</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-magnifier-remove icons"></i><span class="name">magnifier-remove</span></div>
                            <div class="col"><i class="icon-paper-clip icons"></i><span class="name">paper-clip</span></div>
                            <div class="col"><i class="icon-paper-plane icons"></i><span class="name">paper-plane</span></div>
                            <div class="col"><i class="icon-power icons"></i><span class="name">power</span></div>
                            <div class="col"><i class="icon-refresh icons"></i><span class="name">refresh</span></div>
                            <div class="col"><i class="icon-reload icons"></i><span class="name">reload</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-settings icons"></i><span class="name">settings</span></div>
                            <div class="col"><i class="icon-star icons"></i><span class="name">star</span></div>
                            <div class="col"><i class="icon-symbol-female icons"></i><span class="name">symbol-female</span></div>
                            <div class="col"><i class="icon-symbol-male icons"></i><span class="name">symbol-male</span></div>
                            <div class="col"><i class="icon-target icons"></i><span class="name">target</span></div>
                            <div class="col"><i class="icon-credit-card icons"></i><span class="name">credit-card</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-paypal icons"></i><span class="name">paypal</span></div>
                            <div class="col"><i class="icon-social-tumblr icons"></i><span class="name">social-tumblr</span></div>
                            <div class="col"><i class="icon-social-twitter icons"></i><span class="name">social-twitter</span></div>
                            <div class="col"><i class="icon-social-facebook icons"></i><span class="name">social-facebook</span></div>
                            <div class="col"><i class="icon-social-instagram icons"></i><span class="name">social-instagram</span></div>
                            <div class="col"><i class="icon-social-linkedin icons"></i><span class="name">social-linkedin</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-social-pinterest icons"></i><span class="name">social-pinterest</span></div>
                            <div class="col"><i class="icon-social-github icons"></i><span class="name">social-github</span></div>
                            <div class="col"><i class="icon-social-google icons"></i><span class="name"></span>social-google</span></div>
                            <div class="col"><i class="icon-social-reddit icons"></i><span class="name">social-reddit</span></div>
                            <div class="col"><i class="icon-social-skype icons"></i><span class="name">social-skype</span></div>
                            <div class="col"><i class="icon-social-dribbble icons"></i><span class="name">social-dribbble</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-social-behance icons"></i><span class="name">social-behance</span></div>
                            <div class="col"><i class="icon-social-foursqare icons"></i><span class="name">social-foursqare</span></div>
                            <div class="col"><i class="icon-social-soundcloud icons"></i><span class="name">social-soundcloud                                              </span></div>
                            <div class="col"><i class="icon-social-spotify icons"></i><span class="name">social-spotify</span></div>
                            <div class="col"><i class="icon-social-stumbleupon icons"></i><span class="name">social-stumbleupon</span></div>
                            <div class="col"><i class="icon-social-youtube icons"></i><span class="name">social-youtube</span></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col"><i class="icon-social-dropbox icons"></i><span class="name">social-dropbox</span></div>
                            <div class="col"><i class="icon-social-vkontakte icons"></i><span class="name">social-vkontakte</span></div>
                            <div class="col"><i class="icon-social-steam icons"></i><span class="name">social-steam</span></div>
                            <div class="col"><i class="icon-xxx icons"></i><span class="name"></span></div>
                            <div class="col"><i class="icon-xxx icons"></i><span class="name"></span></div>
                            <div class="col"><i class="icon-xxx icons"></i><span class="name"></span></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection