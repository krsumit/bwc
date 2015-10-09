$().ready(function() {

                var isDragActive = false;
                // Quicklaunch Widget
                $( "#sortable" ).sortable({
                    cancel: '#sortable li:last-child',
                    start: function(event, ui) {
                        isDragActive = true;
                        $('.dashboard-quick-launch li img').tooltip('hide');
                    },
                    stop: function(event, ui) {
                        isDragActive = false;
                    },
                    containment: 'parent',
                    tolerance: 'pointer'
                });

                // Make widgets sortable
                $( "#photon_widgets" ).sortable({
                    cancel: '.blank-widget, .flip-it',
                    placeholder: 'dashboard-widget-placeholder',
                    start: function(event, ui) {
                        isDragActive = true;
                        $('.widget-holder').addClass('noPerspective');
                        $('.dashboard-quick-launch li img').tooltip('hide');
                    },
                    stop: function(event, ui) {
                        isDragActive = false;
                        $('.widget-holder').removeClass('noPerspective');
                    },
                    tolerance: 'pointer'
                });


                $('.dashboard-quick-launch li img').not('.dashboard-quick-launch li:last-child').tooltip({
                    placement: 'top',
                    html: true,
                    trigger: 'manual',
                    title: '<a href="javascript:;"><span class="left">Edit</span></a><a href="javascript:;"><span class="right">Delete</span></a>'
                });


                var hoverTimeout;
                $('.dashboard-quick-launch li').hover(function () {
                    if (!$(this).find('.tooltip').length){
                        $activeQL = $(this);
                        clearTimeout(hoverTimeout);
                        hoverTimeout = setTimeout(function() {
                            if (isDragActive) return;
                            $activeQL.find('img').tooltip('show');
                        }, 1000);
                    }
                }, function () {
                    clearTimeout(hoverTimeout);
                    $('.dashboard-quick-launch li').find('img').tooltip('hide');
                });
                
                var firstHover = true;
                $('.dashboard-quick-launch li').hover(function(){
                    if (firstHover) {
                        firstHover = false;
                        setTimeout(function(){
                            $.pnotify({
                               title: 'Assignment Alert',
							   type: 'info',
							   text: 'You have 2 New Articles to write'
                            });
                        }, 400);
                    }
                });
            });