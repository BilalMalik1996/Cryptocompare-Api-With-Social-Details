
$all_coin_list = file_get_contents("https://min-api.cryptocompare.com/data/all/coinlist");
$all_coin_list		=	get_object_vars (json_decode($all_coin_list)->Data);

$i = 0;

foreach($all_coin_list as $z=>$y){

  $k = (get_object_vars($y));
  $coin_id      = $k['Id'];
  $coin_title   = $k['CoinName'];
  $coin_name    = $k['FullName'];
  $coin_img     = $k['ImageUrl'];

  $snap_show_detail = file_get_contents('https://www.cryptocompare.com/api/data/coinsnapshotfullbyid/?id='.$coin_id);
  $snap_show_detail = json_decode($snap_show_detail)->Data->General;

  $coin_des = $snap_show_detail->Description;
  $coin_web = $snap_show_detail->WebsiteUrl;
  $coin_twi = $snap_show_detail->Twitter;

  $social_detail = file_get_contents('https://www.cryptocompare.com/api/data/socialstats/?id='.$coin_id);
  $social_detail = json_decode($social_detail)->Data;

  $social['Twitter']      =  $social_detail->Twitter->link;
  $social['Reddit']       =  $social_detail->Reddit->link;
  $social['Facebook']     =  $social_detail->Facebook->link;

  $social_list=[];
  foreach($social as $social_name => $social_link){
      if(!empty($social_link)){
        $social_list[] = array('name'=>$social_name,'url'=>$social_link);
      }
  }

  $post = array(
     	'post_title'      =>  $coin_name,
        'post_type'     =>  'coin',
        'post_content'  =>  $coin_des,
     		'post_status'   =>  'publish'
       );

     $id = wp_insert_post( $post );
     add_post_meta($id, 'coin_id',$coin_id );
     add_post_meta($id, 'coin_select', $z );
     add_post_meta($id, 'coin_logo_url', $coin_img );
     add_post_meta($id, 'coin_website_url',  $coin_web );
     add_post_meta($id, 'coin_twitter_id', $coin_twi );
     add_post_meta($id, 'coin_title',  $coin_title  );

     add_post_meta($id,'coin_social_links',$social_list);

$i++;
echo $i.'<br>';

}
