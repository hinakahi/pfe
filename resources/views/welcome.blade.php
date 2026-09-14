<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiOuakli — Résidence Universitaire Si Ouakli · Tamda, Tizi Ouzou</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('photo/mon_logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
    select#objetSelect {
        width: 100% !important;
        padding: 14px 18px !important;
        font-size: 18px !important;
        border: 2px solid #ccc !important;
        border-radius: 12px !important;
        background: white !important;
        appearance: auto !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
    }
    @media (max-width: 768px) {
        select#objetSelect {
            font-size: 20px !important;
            padding: 16px 20px !important;
        }
    }
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  </head>
  <body>
    <!-- ══════════ NAVBAR ══════════ -->
    <header class="header" id="header">
      <nav class="nav-container">
        <a href="#accueil" class="logo-container">
          <div class="logo-icon">
    <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo SiOuakli">
      </div>
          <div>
            <span class="logo-text">Si<span>Ouakli</span></span>
            <small class="logo-sub">Résidence kasri Mohammed · Tamda</small>
          </div>
        </a>
        <ul class="nav-menu" id="navMenu">
          <li><a href="#accueil" class="nav-link active">Accueil</a></li>
          <li><a href="#services" class="nav-link">Services</a></li>
          <li><a href="#apropos" class="nav-link">À propos</a></li>
          <li><a href="#contact" class="nav-link">Contact</a></li>
          <li>
            <a href="{{ route('login') }}" class="nav-link btn-login">
              <i class="fas fa-sign-in-alt"></i> Se connecter
            </a>
          </li>
        </ul>
        <button class="hamburger" id="hamburger">
          <span></span><span></span><span></span>
        </button>
      </nav>
    </header>

    <!-- ══════════ HERO SLIDER ══════════ -->
    <section id="accueil" class="hero">
      <div class="particles" id="particles"></div>
      <div class="slider-wrapper">
        <div class="slider-container">
          <div class="progress-ring">
            <div class="progress-bar" id="progressBar"></div>
          </div>
          <div class="slider-stage" id="sliderStage"></div>
          <div class="text-overlay" id="textOverlay">
            <span class="slide-badge" id="slideBadge">Résidence Si Ouakli</span>
            <h2 class="slide-title" id="slideTitle">Bienvenue à la Résidence Si Ouakli</h2>
            <p class="slide-description" id="slideDescription">
              Votre espace de vie universitaire au cœur du pôle de Tamda, Tizi Ouzou.
            </p>
          </div>
          <button class="nav-arrow prev" id="prevArrow"><i class="fas fa-chevron-left"></i></button>
          <button class="nav-arrow next" id="nextArrow"><i class="fas fa-chevron-right"></i></button>
          <div class="controls">
            <button class="control-btn" id="playPauseBtn" title="Pause/Lecture">
              <i class="fas fa-pause" id="playIcon"></i>
            </button>
          </div>
          <div class="dots" id="dots"></div>
          <div class="thumbnails-container" id="thumbnails"></div>
        </div>
      </div>
    </section>

    <!-- ══════════ SERVICES ══════════ -->
    <section id="services" class="section services">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag">Ce que nous offrons</span>
          <h2 class="section-title">Tous vos services, en un clic</h2>
          <p class="section-subtitle">
            La résidence Si Ouakli met à votre disposition une plateforme complète
            pour un séjour universitaire serein : hébergement, maintenance et foyer.
          </p>
        </div>
        <div class="services-container">
          <div class="services-tabs reveal-left">
            <div class="service-tab active" data-service="hebergement">
              <span class="service-tab-icon">🏠</span>
              <h3>Hébergement</h3>
              <p>Chambres doubles & individuelles</p>
            </div>
            <div class="service-tab" data-service="maintenance">
              <span class="service-tab-icon">🔧</span>
              <h3>Maintenance</h3>
              <p>Signalement & suivi des pannes</p>
            </div>
            <div class="service-tab" data-service="foyer">
              <span class="service-tab-icon">🛍️</span>
              <h3>Foyer</h3>
              <p>Catalogue & réservation d'articles</p>
            </div>
          </div>
          <div class="services-content reveal-right">
            <div class="service-content active" id="hebergement">
              <div class="service-content-icon">🏠</div>
              <h2>Hébergement <span>confortable</span></h2>
              <p>La résidence Si Ouakli dispose de <strong>2000 lits</strong> répartis dans 8 pavillons modernes. Chaque résidente bénéficie d'un espace sécurisé et bien équipé.</p>
              <p>Via l'application, vous pouvez demander un changement de chambre, un renouvellement, ou suivre vos demandes en temps réel.</p>
              <ul class="service-features">
                <li>Chambres doubles et individuelles (cas exceptionnels)</li>
                <li>Demande de changement de chambre en ligne</li>
                <li>Demande de renouvellement de chambre en ligne</li>
                <li>Suivi en temps réel du statut de vos demandes</li>
                <li>Attribution selon critères ONOU (distance, dossier social)</li>
              </ul>
            </div>
            <div class="service-content" id="maintenance">
              <div class="service-content-icon">🔧</div>
              <h2>Maintenance <span>rapide & efficace</span></h2>
              <p>Signalez une panne (électricité, plomberie, mobilier...) directement depuis votre espace étudiant, sans passer par un registre papier.</p>
              <p>Le technicien reçoit votre demande, la traite et vous tient informée de l'avancement à chaque étape.</p>
              <ul class="service-features">
                <li>Signalement de panne en ligne, en quelques clics</li>
                <li>Suivi en temps réel du statut (en attente, en cours, résolue)</li>
                <li>Historique complet de vos demandes</li>
                <li>Notifications à chaque changement de statut</li>
                <li>Gestion du stock de pièces par le technicien</li>
              </ul>
            </div>
            <div class="service-content" id="foyer">
              <div class="service-content-icon">🛍️</div>
              <h2>Foyer <span>& vie pratique</span></h2>
              <p>Consultez le catalogue d'articles du foyer et réservez ce dont vous avez besoin directement en ligne, sans file d'attente.</p>
              <p>Le responsable du foyer publie les annonces et gère vos réservations en temps réel.</p>
              <ul class="service-features">
                <li>Catalogue d'articles consultable en ligne</li>
                <li>Réservation d'articles en quelques clics</li>
                <li>Annonces du foyer publiées en temps réel</li>
                <li>Suivi de vos réservations</li>
                <li>Notifications de confirmation</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ À PROPOS ══════════ -->
    <section id="apropos" class="section about">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag" style="color: var(--sky3); background: rgba(56, 189, 248, 0.1); border-color: rgba(56, 189, 248, 0.2);">Notre histoire</span>
          <h2 class="section-title">La résidence Si Ouakli en chiffres</h2>
          <p class="section-subtitle">Kasri Mohammed Akli — dit Si Ouakli — accompagne les étudiantes de l'UMMTO depuis 2009.</p>
        </div>
        <div class="about-grid">
          <div class="about-timeline reveal-left">
            <div class="timeline-line"></div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-year">2009</div>
              <div class="timeline-title">Mise en service de la résidence</div>
              <div class="timeline-description">
                Ouverture de la résidence universitaire Kasri Mohammed Akli (Tamda 1) rattachée à la DOU de Tamda pour répondre à la demande croissante en hébergement universitaire.
              </div>
            </div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-year">2026</div>
              <div class="timeline-title">Projet de numérisation Si Ouakli</div>
              <div class="timeline-description">
                Lancement du projet de modernisation numérique visant à digitaliser les trois axes principaux : hébergement (renouvellement et changement de chambre), maintenance (signalement et suivi des pannes) et foyer (catalogue et réservations d'articles).
              </div>
            </div>
            <div class="timeline-item">
              <div class="timeline-dot"></div>
            </div>
          </div>
          <div class="about-tech reveal-right">
            <div class="tech-card">
              <div class="tech-icon">🚀</div>
              <div class="tech-title">Zéro papier, 100% numérique</div>
              <div class="tech-description">Toutes les demandes (maintenance, changement de chambre, réservations foyer) sont traitées en ligne. Fini les notes manuscrites et les files d'attente inutiles.</div>
            </div>
            <div class="tech-card">
              <div class="tech-icon">🔔</div>
              <div class="tech-title">Notifications en temps réel</div>
              <div class="tech-description">L'administration peut diffuser des alertes urgentes (coupures, événements) directement aux 1318 résidentes — remplaçant l'affichage physique limité.</div>
            </div>
            <div class="tech-card">
              <div class="tech-icon">📊</div>
              <div class="tech-title">Tableau de bord & KPI</div>
              <div class="tech-description">L'administration dispose d'indicateurs de performance en temps réel : taux d'occupation, réclamations ouvertes, demandes en attente, statistiques de satisfaction.</div>
            </div>
          </div>
        </div>
        <div class="stats-grid reveal">
          <div class="stat-card"><h4>2000</h4><p>Lits disponibles</p></div>
          <div class="stat-card"><h4>1318</h4><p>Résidentes actuelles</p></div>
          <div class="stat-card"><h4>8</h4><p>Pavillons d'hébergement</p></div>
          <div class="stat-card"><h4>121</h4><p>Employés au service</p></div>
        </div>
      </div>
    </section>

    <!-- ══════════ CONTACT ══════════ -->
    <section id="contact" class="section contact">
      <div class="section-container">
        <div class="section-header reveal">
          <span class="section-tag">Nous contacter</span>
          <h2 class="section-title">Une question ? Écrivez-nous</h2>
          <p class="section-subtitle">Pour toute demande d'information concernant la résidence Si Ouakli ou la plateforme TAMDA 1.</p>
        </div>
        <div class="contact-content">
         <div class="contact-form reveal-left">

    @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:500;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.send') }}">
        @csrf

        <div class="form-group">
            <label>Votre nom complet</label>
            <input type="text" name="nom" placeholder=" saisir votre nom svp .."
                   value="{{ old('nom') }}" />
            @error('nom')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Adresse email</label>
            <input type="email" name="email" placeholder="saisir votre email svp..."
                   value="{{ old('email') }}" />
            @error('email')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

       @php
    $objets = ['Information hébergement', 'Problème technique', 'Accès compte', 'Autre'];
    $objetActuel = old('objet', $objets[0]);
@endphp

<div class="form-group">
    <label>Objet</label>
    <select name="objet" id="objetSelect" class="form-control">
        @foreach($objets as $o)
            <option value="{{ $o }}" {{ $objetActuel == $o ? 'selected' : '' }}>{{ $o }}</option>
        @endforeach
    </select>
</div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" placeholder="Décrivez votre demande…">{{ old('message') }}</textarea>
            @error('message')<span style="color:#ef4444;font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane" style="margin-right: 8px"></i>Envoyer le message
        </button>
    </form>
</div>
          <div class="contact-info reveal-right">
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div class="contact-details">
                <h4>Adresse</h4>
                <p>Pôle universitaire de Tamda<br />Tizi Ouzou, Algérie</p>
              </div>
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d573.6880237066865!2d4.196465435229105!3d36.710720906407225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128dbb0044c1c6a7%3A0x871fca11d9bc1e6!2sR%C3%A9sidence%20fille%20tamda%201!5e1!3m2!1sfr!2sdz!4v1779619757932!5m2!1sfr!2sdz" style="border:0; width:100%; max-width:300px; height:300px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-university"></i></div>
              <div class="contact-details">
                <h4>Rattachement</h4>
                <p>DOU de Tamda<br />Université Mouloud Mammeri (UMMTO)</p>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-clock"></i></div>
              <div class="contact-details">
                <h4>Horaires d'accueil</h4>
                <p>Dimanche – Jeudi : 8h00 – 15h30
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fas fa-shield-alt"></i></div>
              <div class="contact-details">
                <h4>Conditions d'accès</h4>
                <p>Inscription UMMTO + résidence familiale<br />à plus de 30 km de Tizi Ouzou</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ FOOTER ══════════ -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-main">
          <div>
            <div class="footer-logo-wrap">
               <a href="#accueil" class="logo-container">
          <div class="logo-icon">
    <img src="{{ asset('photo/mon_logo.jpg') }}" alt="Logo SiOuakli">
      </div>
          <div>
            <span class="logo-text">Si<span>Ouakli</span></span>
            <small class="logo-sub">Résidence kasri Mohammed · Tamda</small>
          </div>
        </a>
             
             
            </div>
            <p class="footer-description">
              Plateforme numérique de gestion de la résidence universitaire Kasri Mohammed Akli (Si Ouakli) — Tamda, Tizi Ouzou. Un écosystème digital pour les 1318 résidentes.
            </p>
            <div class="footer-social">
              <a href="https://www.facebook.com/dz.onou.15.dout.rut1" class="social-link"><i class="fab fa-facebook-f"></i></a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Services</h4>
            <div class="footer-links">
              <a href="#services" class="footer-link">Hébergement</a>
              <a href="#services" class="footer-link">Maintenance</a>
              <a href="#services" class="footer-link">Foyer</a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Accès rapide</h4>
            <div class="footer-links">
              <a href="{{ route('login') }}" class="footer-link">Se connecter</a>
              <a href="{{ route('login') }}" class="footer-link">S'inscrire</a>
              <a href="#apropos" class="footer-link">À propos</a>
              <a href="#contact" class="footer-link">Contact</a>
            </div>
          </div>
          <div class="footer-section">
            <h4>Administration</h4>
            <div class="footer-links">
              <a href="https://progres.mesrs.dz/webetu/" target="_blank" class="footer-link">PROGRES MESRS</a>
              <a href="#" class="footer-link">DOU Tamda</a>
              <a href="#" class="footer-link">UMMTO</a>
              <a href="#" class="footer-link">ONOU</a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <div class="footer-copyright">© 2026 — Résidence Si Ouakli, Tamda · Tizi Ouzou</div>
          <div class="footer-copyright">Développé dans le cadre du PFE — Université Mouloud Mammeri</div>
        </div>
      </div>
    </footer>

    <script>
      /* ══════════ PARTICULES ══════════ */
      (function () {
        const c = document.getElementById("particles");
        for (let i = 0; i < 28; i++) {
          const p = document.createElement("div");
          p.className = "particle";
          const s = Math.random() * 8 + 3;
          p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random() * 100}%;top:${Math.random() * 100}%;animation-duration:${Math.random() * 8 + 6}s;animation-delay:${Math.random() * 6}s;background:rgba(${Math.random() > 0.5 ? "56,189,248" : "14,165,233"},${Math.random() * 0.5 + 0.2})`;
          c.appendChild(p);
        }
      })();

      /* ══════════ IMAGES SLIDER ══════════ */
      const SLIDES = [
        {
          url: "{{ asset('photo/7.jpg') }}",
          thumb: "{{ asset('photo/7.jpg') }}",
          badge: "Résidence Si Ouakli",
          title: "Bienvenue à la Résidence Si Ouakli",
          description: "2000 lits, 8 pavillons modernes — votre espace de vie universitaire à Tamda, Tizi Ouzou.",
        },
        {
          url: "{{ asset('photo/564651935_802743812516379_3548021131265954541_n.jpg') }}",
          thumb: "{{ asset('photo/564651935_802743812516379_3548021131265954541_n.jpg') }}",
          badge: "Foyer",
          title: "Catalogue du foyer en ligne",
          description: "Réservez vos articles sans file d'attente — consultez le catalogue et suivez vos réservations.",
        },
        {
          url: "{{ asset('photo/5.png') }}",
          thumb: "{{ asset('photo/5.png') }}",
          badge: "Services intégrés",
          title: "Tous vos services en un clic",
          description: "Hébergement, maintenance et foyer — tout depuis votre espace personnel.",
        },
        {
          url: "{{ asset('photo/3.png') }}",
          thumb: "{{ asset('photo/3.png') }}",
          badge: "Services intégrés",
          title: "Tous vos services en un clic",
          description: "Hébergement, maintenance et foyer — tout depuis votre espace personnel.",
        },
        {
          url: "{{ asset('photo/548127153_778558304934930_5767677889783360502_n.jpg') }}",
          thumb: "{{ asset('photo/548127153_778558304934930_5767677889783360502_n.jpg') }}",
          badge: "Maintenance",
          title: "Signalement de pannes numérique",
          description: "Signalez une panne en quelques secondes et suivez son traitement en temps réel.",
        },
        {
          url: "{{ asset('photo/6.png') }}",
          thumb: "{{ asset('photo/6.png') }}",
          badge: "Hébergement",
          title: "Votre chambre, votre confort",
          description: "Demandez un changement ou un renouvellement de chambre directement en ligne.",
        },
        {
          url: "{{ asset('photo/571316701_809531668504260_2327986354187602315_n.jpg') }}",
          thumb: "{{ asset('photo/571316701_809531668504260_2327986354187602315_n.jpg') }}",
          badge: "Résidence Si Ouakli",
          title: "Un cadre de vie universitaire moderne",
          description: "1318 résidentes accompagnées au quotidien à travers une plateforme 100% numérique.",
        },
      ];

      /* ══════════ SLIDER LOGIC ══════════ */
      // ─── FIX MOBILE : on détecte si on est sur un petit écran ───
      // Sur mobile, on désactive l'effet "cube 3D à tranches" (trop lourd,
      // provoque un effet de damier) et on le remplace par un simple fondu.
      const IS_MOBILE = window.innerWidth <= 768;

      class SiOuakliSlider {
        constructor() {
          this.idx = 0;
          this.anim = false;
          this.slices = 10;
          this.timer = null;
          this.playing = true;
          this.face = 0;
          this.init();
        }
        init() {
          this.buildSlices();
          this.buildDots();
          this.buildThumbs();
          this.events();
          this.loadImages();
          this.play();
        }
        buildSlices() {
          const st = document.getElementById("sliderStage");

          // FIX MOBILE : une seule image pleine largeur, pas de découpage
          // en tranches (qui créait des coutures/lignes verticales visibles).
          if (IS_MOBILE) {
            const sc = document.createElement("div");
            sc.className = "slice-container mobile-full";
            const cube = document.createElement("div");
            cube.className = "slice-cube";
            const face = document.createElement("div");
            face.className = "slice-face face-1";
            const img = document.createElement("div");
            img.className = "slice-image mobile-full-image";
            face.appendChild(img);
            cube.appendChild(face);
            sc.appendChild(cube);
            st.appendChild(sc);
            return;
          }

          for (let i = 0; i < this.slices; i++) {
            const sc = document.createElement("div");
            sc.className = "slice-container";
            const cube = document.createElement("div");
            cube.className = "slice-cube";
            for (let f = 1; f <= 4; f++) {
              const face = document.createElement("div");
              face.className = `slice-face face-${f}`;
              const img = document.createElement("div");
              img.className = "slice-image";
              img.style.width = (this.slices * 100) + "%";
              img.style.left = -(i * 100) + "%";
              face.appendChild(img);
              cube.appendChild(face);
            }
            sc.appendChild(cube);
            st.appendChild(sc);
          }
        }
        setImage(faceIdx, slideIdx) {
          const url = SLIDES[slideIdx].url;
          if (IS_MOBILE) {
            // FIX MOBILE : une seule image, pas de recherche par face
            const img = document.querySelector(".mobile-full-image");
            if (img) img.style.backgroundImage = `url(${url})`;
            return;
          }
          const faces = document.querySelectorAll(`.slice-face.face-${faceIdx + 1} .slice-image`);
          faces.forEach((img) => (img.style.backgroundImage = `url(${url})`));
        }
        loadImages() {
          SLIDES.forEach((_, i) => {
            if (i === this.idx) this.setImage(this.face, i);
          });
          this.updateText();
          this.resetProgress();
        }
        rotate() {
          if (this.anim) return;
          this.anim = true;

          // FIX MOBILE : fondu simple (opacité) sur l'unique image pleine largeur
          if (IS_MOBILE) {
            const cube = document.querySelector(".slice-cube");
            if (cube) cube.style.opacity = "0";
            setTimeout(() => {
              this.setImage(0, this.idx);
              if (cube) cube.style.opacity = "1";
              this.anim = false;
            }, 300);
            return;
          }

          const nextFace = (this.face + 1) % 4;
          this.setImage(nextFace, this.idx);
          const cubes = document.querySelectorAll(".slice-cube");
          cubes.forEach((c) => {
            c.classList.remove("rotate-0", "rotate-1", "rotate-2", "rotate-3");
            const cur = parseInt(c.dataset.rot || 0);
            const nxt = (cur + 1) % 4;
            c.dataset.rot = nxt;
            c.classList.add(`rotate-${nxt}`);
          });
          this.face = nextFace;
          setTimeout(() => (this.anim = false), 1200);
        }
        go(i) {
          if (this.anim || i === this.idx) return;
          this.idx = (i + SLIDES.length) % SLIDES.length;
          this.rotate();
          this.updateText();
          this.updateDots();
          this.updateThumbs();
          this.resetProgress();
        }
        next() { this.go(this.idx + 1); }
        prev() { this.go(this.idx - 1); }
        updateText() {
          const ov = document.getElementById("textOverlay");
          ov.classList.add("hiding");
          setTimeout(() => {
            document.getElementById("slideBadge").textContent = SLIDES[this.idx].badge;
            document.getElementById("slideTitle").textContent = SLIDES[this.idx].title;
            document.getElementById("slideDescription").textContent = SLIDES[this.idx].description;
            ov.classList.remove("hiding");
          }, 350);
        }
        buildDots() {
          const d = document.getElementById("dots");
          SLIDES.forEach((_, i) => {
            const dot = document.createElement("div");
            dot.className = "dot" + (i === 0 ? " active" : "");
            dot.onclick = () => this.go(i);
            d.appendChild(dot);
          });
        }
        updateDots() {
          document.querySelectorAll(".dot").forEach((d, i) => d.classList.toggle("active", i === this.idx));
        }
        buildThumbs() {
          const c = document.getElementById("thumbnails");
          SLIDES.forEach((s, i) => {
            const t = document.createElement("div");
            t.className = "thumbnail" + (i === 0 ? " active" : "");
            t.style.backgroundImage = `url(${s.thumb})`;
            t.onclick = () => this.go(i);
            c.appendChild(t);
          });
        }
        updateThumbs() {
          document.querySelectorAll(".thumbnail").forEach((t, i) => t.classList.toggle("active", i === this.idx));
        }
        resetProgress() {
          const pb = document.getElementById("progressBar");
          pb.classList.remove("active");
          void pb.offsetWidth;
          if (this.playing) pb.classList.add("active");
        }
        play() {
          this.timer = setInterval(() => { this.next(); }, 4500);
        }
        pause() {
          clearInterval(this.timer);
          this.timer = null;
        }
        toggle() {
          this.playing = !this.playing;
          const ic = document.getElementById("playIcon");
          if (this.playing) {
            this.play();
            ic.className = "fas fa-pause";
            document.getElementById("progressBar").classList.add("active");
          } else {
            this.pause();
            ic.className = "fas fa-play";
            document.getElementById("progressBar").classList.remove("active");
          }
        }
        events() {
          document.getElementById("nextArrow").onclick = () => {
            this.next();
            if (this.playing) { this.pause(); this.play(); }
          };
          document.getElementById("prevArrow").onclick = () => {
            this.prev();
            if (this.playing) { this.pause(); this.play(); }
          };
          document.getElementById("playPauseBtn").onclick = () => this.toggle();
        }
      }
      const slider = new SiOuakliSlider();

      /* ══════════ NAVBAR SCROLL ══════════ */
      window.addEventListener("scroll", () => {
        document.getElementById("header").classList.toggle("scrolled", window.scrollY > 60);
        const links = document.querySelectorAll(".nav-link:not(.btn-login)");
        const sections = ["accueil", "services", "apropos", "contact"];
        let cur = "accueil";
        sections.forEach((id) => {
          const el = document.getElementById(id);
          if (el && window.scrollY >= el.offsetTop - 120) cur = id;
        });
        links.forEach((l) => l.classList.toggle("active", l.getAttribute("href") === "#" + cur));
      });

      /* ══════════ HAMBURGER ══════════ */
      document.getElementById("hamburger").onclick = function () {
        this.classList.toggle("active");
        document.getElementById("navMenu").classList.toggle("active");
      };

      /* ══════════ SERVICES TABS ══════════ */
      document.querySelectorAll(".service-tab").forEach((tab) => {
        tab.onclick = function () {
          document.querySelectorAll(".service-tab").forEach((t) => t.classList.remove("active"));
          document.querySelectorAll(".service-content").forEach((c) => c.classList.remove("active"));
          this.classList.add("active");
          document.getElementById(this.dataset.service).classList.add("active");
        };
      });

      /* ══════════ SCROLL REVEAL ══════════ */
      const obs = new IntersectionObserver(
        (entries) => { entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add("visible"); }); },
        { threshold: 0.12 }
      );
      document.querySelectorAll(".reveal,.reveal-left,.reveal-right").forEach((el) => obs.observe(el));

      (function () {
        const trigger = document.getElementById("customSelectTrigger");
        const options = document.getElementById("customSelectOptions");
        const label = document.getElementById("customSelectLabel");
        const nativeSelect = document.getElementById("objetSelect");
        if (!trigger) return;
        trigger.onclick = function () {
          trigger.classList.toggle("open");
          options.classList.toggle("open");
        };
        options.querySelectorAll("li").forEach((li) => {
          li.onclick = function () {
            options.querySelectorAll("li").forEach((el) => el.classList.remove("selected"));
            this.classList.add("selected");
            label.textContent = this.dataset.value;
            nativeSelect.value = this.dataset.value;
            trigger.classList.remove("open");
            options.classList.remove("open");
          };
        });
        document.addEventListener("click", function (e) {
          if (!trigger.contains(e.target) && !options.contains(e.target)) {
            trigger.classList.remove("open");
            options.classList.remove("open");
          }
        });
      })();
    </script>
  </body>
</html>