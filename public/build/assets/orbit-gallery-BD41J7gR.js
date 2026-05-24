function e(e,t,n,r){let i=document.getElementById(e),a=document.getElementById(`orbitSection`);if(!i||!a)return;let o=Array.from(i.querySelectorAll(`.orbit-card`)),s=o.map((e,t)=>({card:e,angle:360/o.length*t,radius:180+n+Math.random()*(r-n),offsetY:(Math.random()-.5)*180,rotateZ:(Math.random()-.5)*2})),c=0,l=null,u=!1,d=!document.hidden,f=null;function p(e){if(!u||!d){l=null,f=null;return}f||=e;let n=Math.min(e-f,32);f=e,c+=n/16.67*t,s.forEach(e=>{let t=e.angle+c,n=Math.cos(t*Math.PI/180),r=(n+1)/2,i=.72+r*.55,a=.82+r*.18,o;o=n>0?120+Math.floor(n*100):20+Math.floor((n+1)*40),e.card.style.transform=`
                translate(-50%, -50%)
                rotateY(${t}deg)
                translateZ(${e.radius+n*120}px)
                translateY(${e.offsetY}px)
                rotateY(${-t}deg)
                rotateZ(${e.rotateZ}deg)
                scale(${i})
            `,e.card.style.filter=`brightness(${a})`,e.card.style.opacity=1,e.card.style.zIndex=o}),l=requestAnimationFrame(p)}function m(){l||!u||!d||(f=null,l=requestAnimationFrame(p))}function h(){l&&(cancelAnimationFrame(l),l=null,f=null)}new IntersectionObserver(e=>{e.forEach(e=>{u=e.isIntersecting,u?m():h()})},{threshold:.15,rootMargin:`200px 0px`}).observe(a),document.addEventListener(`visibilitychange`,()=>{d=!document.hidden,d?m():h()})}e(`orbitWorld`,.09,370,520);