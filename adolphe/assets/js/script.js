// assets/js/script.js
document.addEventListener('DOMContentLoaded',()=>{
  // loader
  setTimeout(()=>{const l=document.getElementById('loader'); if(l){l.style.opacity='0'; setTimeout(()=>l.remove(),500);}},600);
  if(window.AOS) AOS.init({duration:700,once:true});
  // counters
  document.querySelectorAll('.stat-num').forEach(el=>{
    const target=parseInt(el.dataset.count||el.textContent,10);
    let cur=0; const step=Math.ceil(target/60);
    const t=setInterval(()=>{cur+=step; if(cur>=target){cur=target; clearInterval(t);} el.textContent=cur;},20);
  });
  // theme
  const btn=document.getElementById('themeToggle');
  const saved=localStorage.getItem('theme');
  if(saved==='light') document.body.classList.add('light');
  if(btn) btn.addEventListener('click',()=>{
    document.body.classList.toggle('light');
    localStorage.setItem('theme', document.body.classList.contains('light')?'light':'dark');
    btn.innerHTML=document.body.classList.contains('light')?'<i class="fa-solid fa-sun"></i>':'<i class="fa-solid fa-moon"></i>';
  });
  // gallery filter
  document.querySelectorAll('[data-filter]').forEach(b=>{
    b.addEventListener('click',()=>{
      const f=b.dataset.filter;
      document.querySelectorAll('[data-filter]').forEach(x=>x.classList.remove('active'));
      b.classList.add('active');
      document.querySelectorAll('.filter-item').forEach(it=>{
        const c=it.dataset.category||'';
        it.style.display=(f==='all'||c===f)?'':'none';
      });
    });
  });
  // lightbox
  const lb=document.getElementById('lightbox');
  if(lb){
    document.querySelectorAll('.lightbox-trigger').forEach(img=>{
      img.addEventListener('click',()=>{lb.querySelector('img').src=img.src; lb.classList.add('active');});
    });
    lb.addEventListener('click',()=>lb.classList.remove('active'));
  }
  // search live
  const s=document.getElementById('liveSearch');
  if(s) s.addEventListener('input',()=>{
    const q=s.value.toLowerCase();
    document.querySelectorAll('.search-item').forEach(it=>{
      it.style.display=it.textContent.toLowerCase().includes(q)?'':'none';
    });
  });
});
