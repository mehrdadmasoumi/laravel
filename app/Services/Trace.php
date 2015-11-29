<?php namespace App\Services;

class Trace {
    public $list = array();
    public $flushNumber = 0;
    public $enabled = true;
    
    public function __construct()
    {
        //
    }
    
    public function __destruct()
    {
    }
    
    public function add($val, $title=null, $dir='ltr')
    {
        if(!$this->enabled) return false;
        $title = is_null($title) ? gettype($val) : $title;
        $val = is_null($val) ? '[NULL]' : $val;
        $this->list[] = array('value' => print_r($val, 1), 'title'=>$title, 'dir'=>$dir);

        return true;
    }
    
    public function addEx($val, $title=null, $exclude=null)
    {
        if(!$this->enabled) return false;
        
        $exclude = array_map('trim', explode(',', $exclude));
                
        if(is_object($val) or is_array($val)) {
            foreach($val as $k=>$v){
                if(!array_search($k, $exclude)) {
                    is_array($val) ? $res[$k] = $v : $res->$k = $v;
                }
            }
        } else {
            $res = $val;
        }
        $this->add($res, $title);
        return @$res;
    }
    
    public function alert($val)
    {
        if(!headers_sent()) echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
        $res = addcslashes(print_r($val, 1), "\r\n\t\"\'");
        echo "<script>alert('$res')</script>";
    }

    public function flush()
    {
        $this->flushNumber++;
        if($this->enabled and $this->list) {
            $out = '<script type="text/javascript" id="loadTraceScr">
                        loadTrace = function(n) {
                            if (document.body == null) return;
                            if(typeof TRACE_CLASS == "undefined" && document.getElementById("script-trace") == null) {
                                try {
                                    var script = window.top.document.createElement("script");
                                    script.src = "/assets/scripts/custom/trace.js";
                                    script.id = "script-trace";
                                    document.body.appendChild(script);
                                } catch(e) {
                                    console.log(e);
                                }
                            }
                            if (typeof jQuery == "undefined") {
                                try {
                                    var script = document.createElement("script");
                                    script.src = "http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js";
                                    document.body.appendChild(script);
                                    script.onreadystatechange = script.onload = function() {
                                        jQ = jQuery.noConflict();
                                        window.top.traceScript =\'Trace = new TRACE_CLASS(); Trace.list=' . addslashes(json_encode($this->list)) . '; Trace.show();\';
                                    };
                                } catch(e){
                                    console.log(e);
                                };
                            } else {
                                try {
                                    window.top.traceScript =\'Trace = new TRACE_CLASS(); Trace.list=' . addslashes(json_encode($this->list)) . ';Trace.show();\';
                                } catch(e) {
                                    console.log(e);
                                }
                            }    
                            return true;
                        };
                        setTimeout("loadTrace(1)", 100);
                    </script>';
            echo str_replace(array("\n", "\r", "\t"), null, $out);
        }
    }
}