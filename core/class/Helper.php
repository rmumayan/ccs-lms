<?php 
	class Helper
	{
        public static function Generate_Pagination_btn($count,$data_per_pages,$current_page,$showed_extra_pages = 2){
	        $pagination = [];
	        //add previous 
            $valid_pages = ceil($count/$data_per_pages);
	        if (($current_page - 1) > 0) $pagination[] = array('class'=>'','text'=>'<span aria-hidden="true">&laquo;</span>','goto-page'=> $current_page - 1);
            //left pages button
            


            for ($i = $showed_extra_pages; $i > 0 ; $i--) { 
                $lesser_pages = $current_page - $i;
                if ($lesser_pages <= 0){
                    continue;
                } 
                $pagination[] = array('class'=>'','text'=> $lesser_pages,'goto-page'=> $lesser_pages);
            }



            //current button
            $pagination[] = array('class'=>'active','text'=> $current_page,'goto-page'=> '#');

            //right pages button
            for ($i = 1; $i <= $showed_extra_pages ; $i++) { 
                $more_page = $current_page + $i;
                if ($more_page >  $valid_pages){
                    break;
                } 
                $pagination[] = array('class'=>'','text'=> $more_page,'goto-page'=> $more_page);
            }

            //add next 
            if (($current_page + 1) <=  $valid_pages) $pagination[] = array('class'=>'','text'=>'<span aria-hidden="true">&raquo;</span>','goto-page'=> $current_page + 1);
            return $pagination;
        }

	}




