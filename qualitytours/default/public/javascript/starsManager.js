/* @version 1.0 starsManager
 * @author Lucas Forchino
 * @webSite: http://www.jqueryload.com
 * jpsonp rating Example.
 * This example show you how to create a rating using jsonp and jquery.
 */
(function(){
    LKS={};
    LKS.params={};
    LKS.params.stars=5;
    LKS.params.url='http://www.jqueryload.com/examples/stars/test.php?callback=LKS.draw';
    LKS.start= function(){
        $.jsonp( {'url':LKS.params.url});
    }
    LKS.sendVote = function (id,vote){
       // change this line in order to send your vote to your server.
    }
    LKS.draw = function(data){
        $('.stars').each(function(index,item){
            var id=$(item).attr('itemid');
            $(item).attr('value',data[id]);
        });
        $('.stars').each(function(index,item){
            var n;
            var value= $(item).attr('value');
            for (n=0;n<LKS.params.stars;n++){
                var star=$('<a>');
                star.addClass('star');
                star.attr('href',"?c="+n);
                star.attr('vote',n+1);
                if (n<=value)
                {star.addClass('point');}
                $(item).append(star);
            }
        });
        $('.stars a').bind('mouseover',function(data){
            var childs=$(data.target).parent().children();
            childs.each(function(index,item){
                if ($(this).index() <= $(data.target).index())
                    {$(item).addClass('hover');}
                else
                    {$(item).removeClass('hover');}

            })
        })
        $('.stars a').bind('mouseout',function(data){
            var childs=$(data.target).parent().children();
            $(childs).removeClass('hover');
        });
       $('.stars a').bind('click',function(){
              var item=$(this).parent().attr('itemid');
              var vote=$(this).attr('vote');
              LKS.sendVote(vote);
        
       });

    }
}());