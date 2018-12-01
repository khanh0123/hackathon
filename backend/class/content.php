<?php 

include "../helper/common.php";
header("Access-Control-Allow-Origin: *");
/**
 * summary
 */
class Content
{
    private $domain_get = "https://vnreview.vn/";
    public function get_articles($page,$limit)
    {
    	$data = get_content_web($this->domain_get);
        
        
        if(!empty($data)) {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $internalErrors = libxml_use_internal_errors(true);

            $dom->loadHTML($data);

            libxml_use_internal_errors($internalErrors);

            $finder = new DomXPath($dom);

            // $list_content_articles = $finder->query("//*[contains(concat(' ', normalize-space(@tag), ' '), ' h3 ')]");
            // $list_image_articles = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' abtract_thumbnail ')]");
            $list_title_articles = $finder->query("//h3");
            $list_image_articles = $finder->query("//img");

            $list_articles = [];
            
            
            
            for ($i = 0; $i < $list_title_articles->length; $i++) {

                $title = $list_title_articles[$i]->textContent;
                if(strlen($title) < 30) {
                    continue;
                }
                $image = $list_image_articles[$i]->getAttribute('src');
                $link = $list_title_articles[$i]->parentNode->getAttribute("href");
                $link = str_replace($this->domain_get, "", $link);
                $link = preg_replace("/^(\/)(.*)/", "$2", $link);
                $list_articles[] = [
                    'title' => $title,
                    'image' => !preg_match("/^http|https./", $image) ? $this->domain_get.$image : $image,
                    'link' => $link
                ];

            }
            $list_articles = array_reverse($list_articles);
            $list_articles = array_splice($list_articles, $limit*($page-1) , $limit);
            return $list_articles;
        }
        return [];

    }

    public function get_detail_articles($link)
    {
        if(!preg_match("/^http|https./", $link)) {
            $link = $this->domain_get.$link;
        }
        try {
            $content = get_content_web($link);
            if(!empty($content)){
                $dom = new DOMDocument('1.0', 'UTF-8');
                $internalErrors = libxml_use_internal_errors(true);
                $dom->loadHTML($content);
                libxml_use_internal_errors($internalErrors);
                $finder = new DomXPath($dom);
                $title = $finder->query("//h1")[0]->textContent;
                $image = $finder->query("//img")[0]->getAttribute("src");
                $image = !preg_match("/^http|https./", $image) ? $this->domain_get.$image : $image;

                $content = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' journal-content-article ')]")[0];
                // $content = htmlentities(innerHTML($content), ENT_QUOTES, "UTF-8");

                $timeCreated = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' review-displaydate ')]")[0]->textContent;
                
                $html_content = preg_replace("/\"/", "'", innerHTML($content));

                // $content->xpath('root/child[last()]');
                $author = ["Đào Trường","Văn Trường","Quốc Hòa"];
                // echo "<pre>";
                // var_dump($author);
                // echo "</pre>";
                // die();
                
                // foreach ($author as $value) {
                //     var_dump($value);
                // }
                // die;
                
                
                // $author = $author[count($author)-1]->textContent;
                $tags = [];
                

                foreach ($finder->query("//a") as $tag_link) {
                    if($tag_link->getAttribute("class") == "tag"){
                        
                        $tags[] = $tag_link->textContent;
                    }
                }

                $data = [
                    'title' => $title,
                    'content' => $html_content,
                    'author' => $author[rand(0,count($author)-1)],
                    'tags' => $tags,
                    'image' => $image,
                    'timeCreated' => $timeCreated
                ];
                return $data;
            }

        } catch (Exception $e) {
            
        }
        return null;
    }


}