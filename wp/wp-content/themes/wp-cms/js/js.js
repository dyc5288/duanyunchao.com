/*
power by: http://www.wxwdesign.cn
*/
//主函数
var s=function(){
    var interv=5000; //切换间隔时间
    var interv2=0; //切换速速
    var opac1=40; //文字背景的透明度
    var source="fade_focus" //焦点轮换图片容器的id名称
    //获取对象
    function getTag(tag,obj){
        if(obj==null){
            return document.getElementsByTagName(tag)
        }else{
            return obj.getElementsByTagName(tag)
        }
    }
    function getid(id){
        return document.getElementById(id)
    };
    var opac=0,j=0,t=63,num,scton=0,timer,timer2,timer3;
    var id=getid(source);
    id.removeChild(getTag("div",id)[0]);
    var li=getTag("li",id);
    //var div=document.createElement("div");
    var title=document.createElement("div");
    var span=document.createElement("span");
    var button_list=document.createElement("div");
    button_list.className="button_list";
    for(var i=0;i<li.length;i++){
        var a=document.createElement("a");
        a.innerHTML=i+1;
        a.onclick=function(){
            clearTimeout(timer);
            clearTimeout(timer2);
            clearTimeout(timer3);
            j=parseInt(this.innerHTML)-1;
            scton=0;
            t=63;
            opac=0;
            fadeon();
        };
    
        a.className="button_css";
        a.onmouseover=function(){
            this.className="button_hover"
        };
        
        a.onmouseout=function(){
            this.className="button_css";
            sc(j)
        };
        
        button_list.appendChild(a);
    }
    //控制图层透明度
    function alpha(obj,n){
        if(document.all){
            obj.style.filter="alpha(opacity="+n+")";
        }else{
            obj.style.opacity=(n/100);
        }
    }
    //控制焦点按钮
    function sc(n){
        for(var i=0;i<li.length;i++){
            button_list.childNodes[i].className="button_css"
        };
        
        button_list.childNodes[n].className="button_hover";
    }
    title.className="img_bottom";
    title.appendChild(span);
    alpha(title,opac1);
    id.className="focus_d1";
    //div.className="focus_d2";
    //id.appendChild(div);
    id.appendChild(title);
    id.appendChild(button_list);
    //渐显
    var fadeon=function(){
        opac+=100;
        li[j].className = 'fadeon';
        //div.innerHTML=li[j].innerHTML;
        span.innerHTML=getTag("img",li[j])[0].alt;
        //alpha(div,opac);
        if(scton==0){
            sc(j);
            num=-2;
            scrolltxt();
            scton=1
        };
        
        if(opac<100){
            timer=setTimeout(fadeon,interv2)
        }else{
            timer2=setTimeout(fadeout,interv);
        };

    }
    //渐隐
    var fadeout=function(){
        opac-=100;
        li[j].className = 'fadeout';
        //div.innerHTML=li[j].innerHTML;
        //alpha(div,opac);
        if(scton==0){
            num=2;
            scrolltxt();
            scton=1
        };
        
        if(opac>0){
            timer=setTimeout(fadeout,interv2)
        }else{
            if(j<li.length-1){
                j++
            }else{
                j=0
            };
            
            fadeon()
        };
    
    }
    //滚动文字
    var scrolltxt=function(){
        t+=num;
        span.style.marginTop=t+"px";
        if(num<0&&t>3){
            timer3=setTimeout(scrolltxt,interv2)
        }else if(num>0&&t<62){
            timer3=setTimeout(scrolltxt,interv2)
        }else{
            scton=0
        }
    };
    fadeon();
}
//初始化
window.onload=s;