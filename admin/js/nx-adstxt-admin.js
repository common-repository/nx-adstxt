"use strict";

(function($) 
{
    
    var nxAdstxt;

	$(function() 
	{
        nxAdstxt = {
            config: JSON.parse($('#nxAdstxtData').html())
        };

        nxAdstxtInit.apply(
        {
          
            bind: {},
            $area:  $("#plugin-nx-adstxt"),
            removeEl: function($el)
            {
                $el.hide("fast", function() 
                {
                    $el.remove();
                });
            },
            scrollTo: function($el)
            {
                $('html, body').stop().animate({
                    scrollTop: $el.offset().top - 100
                }, 300);
            },
            uuidv4: function()
            {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },
            codeMirror: function(myTextArea, opts)
            {
                if (myTextArea && window.wp.CodeMirror) {

                    var cmOpts = {
                        value: myTextArea.value,
                    };

                    for (var index in opts) {
                        cmOpts[index] = opts[index];
                    }

                    var myCodeMirror = wp.CodeMirror(function(elt) 
                    {
                        myTextArea.parentNode.appendChild(elt);
                        myTextArea.style.display = 'none';
                        
                    }, cmOpts);

                    myCodeMirror.on('change',function(cMirror)
                    {
                        myTextArea.value = cMirror.getValue();
                    });
                }
            }
        }, [$, function() 
        {
            for (var index in this.bind) {
               this.bind[index].apply(this);
            }
        }]);
    });


    if (window.wp && window.wp.CodeMirror) {
        window.wp.CodeMirror.defineSimpleMode("adstxt", {
            start: [
                {
                    regex: /^\s*#.*$/,
                    sol: true,
                    token: "comment"
                },
                {
                    regex: /^(\s*\w*)(\s*=\s*)([^#]+)(\s*#.*)?$/,
                    sol: true,
                    token: ["def","operator", "string", "comment"]
                },
                {
                    regex: /^([^,#]+)(,\s*)([^,#]+)(,\s*)(\w+\s*)(,\s*[^\s,#]+)?(\s*#.*)?$/i,
                    sol: true,
                    token:  ["string", null, "string", null, "keyword", "string", "comment"]
                }
              ],
            meta: {
              lineComment: "#"
            }
        });
    
        function verify(text, options) {
            var lines = text.split("\n");
            var messages = [];
    
            for (var index = 0; index < lines.length; index++) {
                var line = lines[index];
                if (
                line.trim().length !== 0 && 
                !line.match(/^\s*#.*$/)) {
                    if (!line.match(/^(\s*[^,#\s]+)(\s*,\s*)([^,#]+)(,\s*)(\w+\s*)(,\s*[^\s,#]+)?(\s*#.*)?$/i)) {
                        if (!line.match(/^(\s*\w*)(\s*=\s*)(.+)(\s*)$/)) {
                            messages.push({
                                from: window.wp.CodeMirror.Pos(index, 0),
                                to:   window.wp.CodeMirror.Pos(index, line.length + 1),
                                message: nxAdstxt.config.entry_invalid,
                                severity : "error"
                            });
                        }
    
                    } else {
                        var values = line.split(",");
    
                        if (values[2].match(/RESELLER|DIRECT/i) === null) {
                            var start = line.indexOf(values[2].trim());
                            var parsed = values[2].split("#");
                            var to = start + parsed[0].trim().length;
    
                            messages.push({
                                from: window.wp.CodeMirror.Pos(index, start),
                                to:   window.wp.CodeMirror.Pos(index, to),
                                message: nxAdstxt.config.type_invalid,
                                severity : "error"
                            });
                        }
    
                        var domain_regex = /^((?=[a-z0-9-]{1,63}\.)(xn--)?[a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,63}$/i;
    
                        if (values[0].trim().match(domain_regex) === null) {
                            var start = line.indexOf(values[0].trim());
                            var to  =  start + values[0].trim().length;
    
                            messages.push({
                                from: window.wp.CodeMirror.Pos(index, start),
                                to:   window.wp.CodeMirror.Pos(index, to),
                                message:  nxAdstxt.config.domain_invalid,
                                severity : "error"
                            });
                        }
                    }
                }  
                
                if (line.trim().length == 0 ) {
       
                }
            }
    
            return messages;
        }
    
        window.wp.CodeMirror.registerHelper("lint", "adstxt", function(text, options) 
        {
            var found = verify(text, options);
            return found;
        });
    
    }
    
    var nxAdstxtInit = function($, callback)
    {
        var self = this;
    
        this.bind.codeMirror = function()
        {
            var el = document.getElementById('nxAdstxtStyle');
            var minLines = 5;
            var givenLines = el.value.split("\n").length;
            var startingValue = el.value;
            
            if (givenLines < minLines) {
         
                var addLines = minLines - givenLines;
    
                for (var i = 0; i < addLines; i++) {
                    startingValue += '\n';
                }
            }
    
            var editor = this.codeMirror(document.getElementById('nxAdstxtStyle'),
            {
                mode: "adstxt",
                lineNumbers: true,
                styleActiveLine: true,
                smartIndent: false,
                value: startingValue,
                gutters: ["CodeMirror-lint-markers"],
                lint: true,
                theme: "mdnx"
            });
    
        };
    
        var hashCode = function(s){
            return s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);              
        }
    
        this.bind.urls = function()
        {	
            var $urlTable = $('#nxAdstxtUrls table', this.$area);
            var $urlModel = $($('#nxAdstxtUrls .template').html());
    
            $urlTable.on('click', '.btn-remove', function()
            {
                var $el = $(this);
                var $tr = $el.closest("tr");
                var $name = $tr.find('input[data-url]');
    
                if (!$tr.is('[data-init]') && 
                    confirm(nxAdstxt.config.remove_url.replace('%name%', $name.val()))) {
                    self.removeEl($tr);
                } else if ($tr.is('[data-init]')) {
                    self.removeEl($tr);
                }
    
                return false;
            });
    
            $('[data-add-url]', this.$area).on('click', function(e) 
            {
                e.preventDefault();
                var $newData   = $urlModel.clone();
                var $input     = $newData.find('input[data-url]');
    
                $urlTable.find('tbody').append($newData);
    
                if ($input.length) {
                    $input.focus();
                }
    
                self.scrollTo($newData);
                return false;
            });
    
            $urlTable.on('input', 'input[data-url]', function() 
            {
                var $el = $(this);
                var val = $el.val();
                var hash = hashCode(val);
                var fieldSource = $el.attr("data-field");
                var fieldName = fieldSource.replace("%fieldname%", hash);
                $el.attr("name", fieldName);
            });
    
    
        }; // bindUrls
    
        this.bind.helpToggle = function() {
            $(document).on("click", "a[data-show-help]", function(e) 
            {
                e.preventDefault();
                var $el = $(this).parent().next();
                $el.slideToggle("fast");

                return false;
            });
        }
    
        callback.apply(this);
    }    
})(jQuery);