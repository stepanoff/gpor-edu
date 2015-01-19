<div style="font-size: 0px; overflow:hidden" id="topBigAdb"></div>
<div style="font-size: 0px; overflow:hidden" id="topAdb"></div>
<script src="http://t.66.ru/external/adliftQueue/adliftQueue.js?rnd=201407231537"  type="text/javascript"></script>

<script type="text/javascript">
var doubleScraperEnable = false; // have no idea what's that
var adliftParseResponse = false;
var adliftBannersQueue = false;
var doubleScraperEnable = false; // have no idea what's that


$(document).ready(function(){

var el = $("#comments");
var adMaxOffset = 0;
var showWideScrollBanner = false; 
if (el.length) {
        adMaxOffset = el.offset().top;
        if (adMaxOffset > 2600)
            adMaxOffset = 2600;
        if (adMaxOffset > 1000) {
            showWideScrollBanner = true;
        }
        else {
            adMaxOffset = false;
        }
}

adliftBannersQueue = new adliftQueue({
        'banners' : [
            {
                'identifier' : 'topBigBanner',
                'hideOn' : false,
                'adliftSlotUid' : '5302de43652a0479f98f05e2',
                'priority' : 10,
                'targetId' : 'topBigAdb'
            },
            {
                'identifier' : 'topBanner',
                'hideOn' : ['wideBanner', 'topBigBanner'],
                'adliftSlotUid' : '4fbe2a8b714cbf1cd500001f',
                'targetId' : 'topAdb'
            },
            {
                'identifier' : 'wideBanner',
                'hideOn' : ['topBigBanner'],
                'adliftSlotUid' : '52a175f549ac1732af5c6918',
                'priority' : 20,
                'targetId' : 'wideAdb',
                'behaviors' : {
                    'fixed' : {
                        'content' : '#wideAdb',
                        'parent' : '#body',
                        'maxOffset' : adMaxOffset,
                        'after': '.b-fluid-fixed__fluid'
                    }
                }
            },
            {
                'identifier' : 'wideScrollBanner',
                'hideOn' : showWideScrollBanner ? (['topBigBanner', 'newsSkyscraper', 'newsAutoSkyscraper']) : true,
                'insertAfterLoad' : false,
                'adliftSlotUid' : '54754acf652a044af835bb03',
                'priority' : 50,
                'targetId' : 'wideScrollAdb',
                'behaviors' : {
                    'loadOnScroll' : {
                        'content' : '#wideScrollAdb',
                        'parentSelector' : '#body',
                        'verticalOffset' : adMaxOffset
                    }
                }
            },
            {
                'identifier' : 'newsSkyscraper',
                'hideOn' : ['wideBanner', 'topBigBanner'],
                'adliftSlotUid' : '4fbb7c5b714cbf0ced000001',
                'targetId' : 'newsRightAdb',
                'behaviors' : {
                    'fixed' : {
                        'content' : '#runBanner',
                        'parent' : '#body',
                        'after': '.page-col-11-span-9'
                    }
                }
            },
            {
                'identifier' : 'newsAutoSkyscraper',
                'hideOn' : ['wideBanner', 'topBigBanner'],
                'adliftSlotUid' : '4fbdea51714cbf0ff700005c',
                'targetId' : 'newsAutoRightAdb',
                'behaviors' : {
                    'fixed' : {
                        'content' : '#runBanner',
                        'parent' : '#body',
                        'after': '.page-col-11-span-9'
                    }
                }
            },
            {
                'identifier' : 'skyscraper',
                'hideOn' : ['wideBanner', 'topBigBanner'],
                'adliftSlotUid' : '4fbb7c5b714cbf0ced000001',
                'targetId' : 'rightAdb',
                'behaviors' : {
                    'fixed' : {
                        'content' : '#runBanner',
                        'parent' : '#body',
                        'after': '.page-col-16-span-5'
                    }/*,
                    'refreshable' : {
                        'timeInterval': 60,
                        'minTimeInterval': 60,
                        'verticalInterval': 2500
                    }*/
                }
            },
            {
                'identifier' : 'middleWide',
                'hideOn' : false,
                'adliftSlotUid' : '52a8460649ac174e355c691a',
                'targetId' : 'middleWideAdb',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'half_skyscraper',
                'hideOn' : false,
                'adliftSlotUid' : '521b248149ac1750a52de780',
                'targetId' : 'midAdb',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'weatherWide',
                'hideOn' : false,
                'adliftSlotUid' : '515c218f49ac17297e1db410',
                'targetId' : 'weatherWideAdb',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'logosAdb',
                'hideOn' : false,
                'adliftSlotUid' : '542a70ba652a040fe7bcc6d9',
                'targetId' : 'logosAdb',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'empty1',
                'hideOn' : false,
                'adliftSlotUid' : '53830152652a04607cdc2d6d',
                'targetId' : 'emptyAdb1',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'empty2',
                'hideOn' : false,
                'adliftSlotUid' : '53abbe98652a043a783f07cf',
                'targetId' : 'emptyAdb2',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'empty3',
                'hideOn' : false,
                'adliftSlotUid' : '53abbeab652a043a783f07d0',
                'targetId' : 'emptyAdb3',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'empty4',
                'hideOn' : false,
                'adliftSlotUid' : '53abbebb652a043a783f07d1',
                'targetId' : 'emptyAdb4',
                'behaviors' : {
                }
            },
            {
                'identifier' : 'empty5',
                'hideOn' : false,
                'adliftSlotUid' : '53abbec6652a043a753f07ce',
                'targetId' : 'emptyAdb5',
                'behaviors' : {
                }
            }

        ]
    });

adliftParseResponse = function(data) {
    adliftBannersQueue.parseResponse(data);
}

    adliftBannersQueue.init();
});
</script>
