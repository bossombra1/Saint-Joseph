<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class TranslateResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response=$next($request);
        if(app()->getLocale()!=='fr'||!str_contains((string)$response->headers->get('Content-Type'),'text/html')) return $response;
        $content=$response->getContent();
        if(!is_string($content)||$content==='') return $response;
        $translations=json_decode(file_get_contents(lang_path('ui.fr.json')),true)?:[];
        $content=preg_replace_callback('/>([^<>]+)</u',function($m)use($translations){
            $translation=$this->translateValue($m[1],$translations);
            if($translation===null) return $m[0];
            $lead=substr($m[1],0,strlen($m[1])-strlen(ltrim($m[1])));
            $trail=substr($m[1],strlen(rtrim($m[1])));
            return '>'.$lead.e($translation).$trail.'<';
        },$content);
        foreach(['title','aria-label','placeholder','alt','label'] as $at){
            $content=preg_replace_callback('/('.$at.'=)(["\'])(.*?)\2/u',function($m)use($translations){
                $translation=$this->translateValue($m[3],$translations);
                return $translation!==null?$m[1].$m[2].e($translation).$m[2]:$m[0];
            },$content);
        }
        $response->setContent($content);
        return $response;
    }

    private function translateValue(string $value,array $translations): ?string
    {
        $value=trim(html_entity_decode($value,ENT_QUOTES|ENT_HTML5,'UTF-8'));
        $key=preg_replace('/\s+/u',' ',$value)??$value;
        if(isset($translations[$key])) return $translations[$key];

        foreach($translations as $source=>$translation){
            $placeholder=':year';
            $position=strpos($source,$placeholder);
            if($position===false) continue;

            $prefix=substr($source,0,$position);
            $suffix=substr($source,$position+strlen($placeholder));
            if(!str_starts_with($key,$prefix)||!str_ends_with($key,$suffix)) continue;

            $year=substr($key,strlen($prefix),strlen($key)-strlen($prefix)-strlen($suffix));
            if(preg_match('/^\d{4}$/',$year)) return str_replace($placeholder,$year,$translation);
        }

        return null;
    }
}
