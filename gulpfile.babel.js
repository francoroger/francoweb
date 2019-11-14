var pkg = require('./package');

var babel = require("gulp-babel");
var browserSync = require('browser-sync');
var changed = require('gulp-changed');
var composer = require('gulp-uglify/composer');
var del = require('del');
var eslint = require('gulp-eslint');
var Glob = require("glob-fs");
var gulp = require("gulp");
var gulpif = require('gulp-if');
var gutil = require('gulp-util');
var header = require('gulp-header');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var minify = require('gulp-clean-css');
var notifier = require('node-notifier');
var notify = require('gulp-notify');
var path = require("path");
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var reporter = require('postcss-reporter');
var rename = require('gulp-rename');
var rollup = require("gulp-rollup");
var sass = require('gulp-sass');
var size = require('gulp-size');
var stylelint = require('stylelint');
var syntaxScss = require('postcss-scss');
var uglifyjs = require('uglify-js');

const config = {
  name: pkg.name,
  version: pkg.version,
  description: pkg.description,
  author: pkg.author,
  banner: `/**
* ${pkg.name} v${pkg.version}
* ${pkg.homepage}
*
* Copyright (c) ${pkg.author}
* Released under the ${pkg.license} license
*/
`,
  root: __dirname,
  paths: {
    source: 'src',
    build: 'html',
  },

  examples: {
    source: 'resources/src/examples',
    build: 'public/assets/examples'
  },
  fonts: {
    source: 'resources/src/fonts',
    build: 'public/assets/fonts'
  },
  images: {
    source: 'resources/src/photos',
    build: 'public/assets/photos'
  },
  modules: {
    source: 'resources/src/modules',
    build: 'public/assets/modules'
  },
  scripts: {
    source: 'resources/src/es',
    build: 'public/assets/js'
  },
  skins: {
    source: 'resources/src/skins',
    build: 'public/assets/skins',
    include: [
      'resources/src/skins/scss',
      'resources/src/skins',
      'resources/src/scss',
      'resources/src/scss/bootstrap',
      'resources/src/scss/mixins'
    ]
  },
  styles: {
    source: 'resources/src/scss',
    build: 'public/assets/css',
    include: [
      'resources/src/scss',
      'resources/src/scss/bootstrap',
      'resources/src/scss/mixins'
    ]
  },
  vendor: {
    source: 'resources/src/vendor',
    manifest: 'manifest.json',
    dest: 'public/assets/vendor',
    verbose: true,
    override: true,
    ignoreError: false,
    flattenPackages: false,
    flattenTypes: false,
    flatten: false,
    dests: {
      images: "images",
      fonts: "",
      js: "",
      css: ""
    },
    paths: {
      css: 'resources/src/vendor/${package}/src/${file}',
      coffee: 'resources/src/vendor/${package}/${file}',
      es6: 'resources/src/vendor/${package}/src/${file}',
      stylus: 'resources/src/vendor/${package}/src/${file}',
      less: 'resources/src/vendor/${package}/src/${file}',
      sass: 'resources/src/vendor/${package}/src/${file}',
      scss: 'resources/src/vendor/${package}/src/${file}'
    }
  },

  enable: {
    notify: false
  },

  notify: {
    title: pkg.name
  },

  env: 'development',
  production: false,
  setEnv: function(env) {
    if (typeof env !== 'string') return;
    this.env = env;
    this.production = env === 'production';
    process.env.NODE_ENV = env;
  }
};

const browser = browserSync.create();

const handleErrors = function(error) {
  // Send error to notification center with gulp-notify
  if (config.enable.notify) {
    notifier.notify({
      title: config.notify.title,
      subtitle: 'Failure!',
      message: error.message,
    });
  }

  gutil.log(gutil.colors.red(error));
  // Keep gulp from hanging on this task
  this.emit('end');
}

gutil.log(gutil.colors.bold(`ℹ  ${config.name} v${config.version}`));

if (config.production) {
  gutil.log(gutil.colors.bold.green('🚚  Modo Produção'));
} else {
  gutil.log(gutil.colors.bold.green('🔧  Modo Desenvolvimento'));
}

// Clean ***********************************************************************
gulp.task('clean', (done) => {
  return del([`${config.paths.build}/**/*`]).then(() => {
    if (config.enable.notify) {
      notifier.notify({
        title: config.notify.title,
        message: 'Tarefa "Clean" concluída',
      });
    }

    done();
  });
});

// Examples (scripts) **********************************************************
gulp.task('examples:scripts', (done) => {
  const uglify = composer(uglifyjs, console);
  const glob = Glob();
  const files = glob.readdirSync(path.join(config.examples.source, 'es/**/*.js'));

  const globals = {
    jquery: 'jQuery',
    Component: 'Component',
    Plugin: 'Plugin',
    Config: 'Config',
    Site: 'Site',
    GridMenu: "SectionGridMenu",
    Menubar: "SectionMenubar",
    PageAside: "SectionPageAside",
    Sidebar: "SectionSidebar"
  };

  const external = Object.keys(globals);

  return gulp
    .src(`${config.examples.source}/es/**/*.js`)
    .on('error', handleErrors)
    .pipe(
      plumber({
        errorHandler: notify.onError('Erro: <%= error.message %>'),
      })
    )
    .pipe(rollup({
      input: files,
      rollup: require('rollup'),
      allowRealFiles: true,
      globals: globals,
      external: external,
      format: 'es'
    }))
    .pipe(babel({
      babelrc: false,
      presets: [
        [
          'env'
        ]
      ],
      moduleRoot: '',
      moduleIds: true,
      plugins: [
        ["transform-es2015-modules-umd", {
          "globals": globals
        }],
        'transform-object-rest-spread',
        'transform-class-properties',
        'external-helpers'
      ]
    }))
    .pipe(gulpif(config.production, uglify()))
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.examples.build}/js`));
});

// Examples (styles) ***********************************************************
gulp.task('examples:styles', () => {
  return gulp
    .src(`${config.examples.source}/scss/**/*.scss`)
    .pipe(
      plumber({errorHandler: notify.onError('Erro: <%= error.message %>')})
    )
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.styles.include,
      })
    )
    .pipe(postcss())
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.examples.build}/css`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(plumber.stop())
    .pipe(gulp.dest(`${config.examples.build}/css`));
});

// Examples ********************************************************************
gulp.task('examples', gulp.series('examples:scripts', 'examples:styles', (done) => {
  if (config.enable.notify) {
    notifier.notify({
      title: config.notify.title,
      message: 'Tarefa "Examples" concluída',
    });
  }

  done();
}));

// Fonts ***********************************************************************
gulp.task('fonts', () => {
  return gulp
    .src(`${config.fonts.source}/*/*.scss`)
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.styles.include,
      })
    )
    .pipe(postcss())
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.fonts.build}`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.fonts.build}`))
    .pipe(
      gulpif(
        config.enable.notify,
        notify({
          title: config.notify.title,
          message: 'Tarefa "Fonts" concluída',
          onLast: true,
        })
      )
    );
});

// Images **********************************************************************
gulp.task('images', () => {
  return gulp
    .src(`${config.images.source}/**/*.+(png|jpg|jpeg|gif|svg)`)
    .pipe(changed(`${config.images.build}`))
    .pipe(
      plumber({errorHandler: notify.onError('Erro: <%= error.message %>')})
    )
    .pipe(
      imagemin({
        progressive: true,
        use: [pngquant()],
      })
    )
    .pipe(size({showFiles: true}))
    .pipe(plumber.stop())
    .pipe(gulp.dest(`${config.images.build}`))
    .pipe(browser.stream())
    .pipe(
      gulpif(
        config.enable.notify,
        notify({
          title: config.notify.title,
          message: 'Tarefa "Images" concluída',
          onLast: true,
        })
      )
    );
});

// Modules (scripts) **********************************************************
gulp.task('modules:scripts', (done) => {
  const uglify = composer(uglifyjs, console);
  const glob = Glob();
  const files = glob.readdirSync(path.join(config.modules.source, 'es/**/*.js'));

  const globals = {
    jquery: 'jQuery',
    Component: 'Component',
    Plugin: 'Plugin',
    Config: 'Config',
    Site: 'Site',
    GridMenu: "SectionGridMenu",
    Menubar: "SectionMenubar",
    PageAside: "SectionPageAside",
    Sidebar: "SectionSidebar"
  };

  const external = Object.keys(globals);

  return gulp
    .src(`${config.modules.source}/es/**/*.js`)
    .on('error', handleErrors)
    .pipe(
      plumber({
        errorHandler: notify.onError('Erro: <%= error.message %>'),
      })
    )
    .pipe(rollup({
      input: files,
      rollup: require('rollup'),
      allowRealFiles: true,
      globals: globals,
      external: external,
      format: 'es'
    }))
    .pipe(babel({
      babelrc: false,
      presets: [
        [
          'env'
        ]
      ],
      moduleRoot: '',
      moduleIds: true,
      plugins: [
        ["transform-es2015-modules-umd", {
          "globals": globals
        }],
        'transform-object-rest-spread',
        'transform-class-properties',
        'external-helpers'
      ]
    }))
    .pipe(gulpif(config.production, uglify()))
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.modules.build}/js`));
});

// Modules (styles) ***********************************************************
gulp.task('modules:styles', () => {
  return gulp
    .src(`${config.modules.source}/scss/**/*.scss`)
    .pipe(
      plumber({errorHandler: notify.onError('Erro: <%= error.message %>')})
    )
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.styles.include,
      })
    )
    .pipe(postcss())
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.modules.build}/css`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(plumber.stop())
    .pipe(gulp.dest(`${config.modules.build}/css`));
});

// Modules ********************************************************************
gulp.task('modules', gulp.series('modules:scripts', 'modules:styles', (done) => {
  if (config.enable.notify) {
    notifier.notify({
      title: config.notify.title,
      message: 'Tarefa "Modules" concluída',
    });
  }

  done();
}));

// Scripts (lint) **************************************************************
gulp.task('lint:scripts', () => {
  return gulp
    .src(`${config.scripts.source}/**/*.js`, {
      base: './',
      since: gulp.lastRun('lint:scripts'),
    })
    .pipe(eslint({fix: true})) // see http://eslint.org/docs/rules/
    .pipe(eslint.format())
    .pipe(gulp.dest('.'));
});

// Scripts (make) **************************************************************
gulp.task('make:scripts', (done) => {
  const uglify = composer(uglifyjs, console);
  const glob = Glob();
  const files = glob.readdirSync(path.join(config.scripts.source, '**/*.js'));

  const globals = {
    jquery: 'jQuery',
    Component: 'Component',
    Plugin: 'Plugin',
    Config: 'Config',
    Base: 'Base',
    BaseApp: 'BaseApp',
    Site: 'Site',
    GridMenu: "SectionGridMenu",
    Menubar: "SectionMenubar",
    PageAside: "SectionPageAside",
    Sidebar: "SectionSidebar"
  };

  const external = Object.keys(globals);

  return gulp
    .src(`${config.scripts.source}/**/*.js`)
    .on('error', handleErrors)
    .pipe(
      plumber({
        errorHandler: notify.onError('Erro: <%= error.message %>'),
      })
    )
    .pipe(rollup({
      input: files,
      rollup: require('rollup'),
      allowRealFiles: true,
      globals: globals,
      external: external,
      format: 'es'
    }))
    .pipe(babel({
      babelrc: false,
      presets: [
        [
          'env'
        ]
      ],
      moduleRoot: '',
      moduleIds: true,
      plugins: [
        ["transform-es2015-modules-umd", {
          "globals": globals
        }],
        'transform-object-rest-spread',
        'transform-class-properties',
        'external-helpers'
      ]
    }))
    .pipe(gulpif(config.production, uglify()))
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.scripts.build}`));
});

// Scripts *********************************************************************
gulp.task('scripts', gulp.series('lint:scripts', 'make:scripts', (done) => {
  if (config.enable.notify) {
    notifier.notify({
      title: config.notify.title,
      message: 'Tarefa "Scripts" concluída',
    });
  }

  done();
}));

// Skins ***********************************************************************
gulp.task('skins', () => {
  return gulp
    .src(`${config.skins.source}/*.scss`)
    .pipe(
      plumber({errorHandler: notify.onError('Erro: <%= error.message %>')})
    )
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.skins.include,
      })
    )
    .pipe(postcss())
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.skins.build}`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.skins.build}`))
    .pipe(browser.stream())
    .pipe(
      gulpif(
        config.enable.notify,
        notify({
          title: config.notify.title,
          message: 'Tarefa "Skins" concluída',
          onLast: true,
        })
      )
    );
});

// Styles (lint) ***************************************************************
gulp.task('lint:styles', () => {
  return gulp
    .src(`${config.styles.source}/**/*.scss`, {
      base: './',
      since: gulp.lastRun('lint:styles'),
    })
    .pipe(
      postcss(
        [
          stylelint({
            fix: true,
            syntax: 'scss',
          }), // see http://stylelint.io/user-guide/example-config/
          reporter({clearMessages: true, clearReportedMessages: true}),
        ],
        {syntax: syntaxScss}
      )
    )
    .pipe(gulp.dest('./'));
});

// Styles (make) ***************************************************************
gulp.task('make:styles', () => {
  return gulp
    .src(`${config.styles.source}/*.scss`)
    .pipe(
      plumber({errorHandler: notify.onError('Erro: <%= error.message %>')})
    )
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.styles.include,
      })
    )
    .pipe(postcss())
    .pipe(gulpif(config.production, header(config.banner)))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.styles.build}`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(plumber.stop())
    .pipe(gulp.dest(`${config.styles.build}`))
    .pipe(browser.stream());
});

// Styles **********************************************************************
gulp.task('styles', gulp.series('lint:styles', 'make:styles', (done) => {
  if (config.enable.notify) {
    notifier.notify({
      title: config.notify.title,
      message: 'Tarefa "Styles" concluída',
    });
  }

  done();
}));

// Vendor **********************************************************************
gulp.task('clean:vendor', (done) => {
  const manager = new AssetsManager('manifest.json', config.vendor);

  manager.cleanPackages().then(()=>{
    if (config.enable.notify) {
      notifier.notify({
        title: config.notify.title,
        message: 'Vendor clean task complete',
      });
    }
    done();
  });
});

gulp.task('copy:vendor', (done) => {
  // see https://github.com/amazingSurge/assets-manager
  const manager = new AssetsManager('manifest.json', config.vendor);

  manager.copyPackages().then(()=>{
    done();
  });
});

gulp.task('vendor:styles', (done) => {
  return gulp
    .src(`${config.vendor.source}/*/*.scss`)
    .pipe(
      sass({
        precision: 10, // https://github.com/sass/sass/issues/1122
        includePaths: config.styles.include,
      })
    )
    .pipe(postcss())
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.vendor.dest}`))
    .pipe(minify())
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(size({gzip: true, showFiles: true}))
    .pipe(gulp.dest(`${config.vendor.dest}`))
    .pipe(
      gulpif(
        config.enable.notify,
        notify({
          title: config.notify.title,
          message: 'Vendor task complete',
          onLast: true,
        })
      )
    );
});

gulp.task(
  'vendor',
  gulp.series('copy:vendor', 'vendor:styles', (done) => {
    if (config.enable.notify) {
      notifier.notify({
        title: config.notify.title,
        message: 'Vendor task complete',
      });
    }

    done();
  })
);

// Gulp tasks ******************************************************************
gulp.task('dist', gulp.series(
  'make:styles',
  'make:scripts',
  'images',
  'examples',
  'modules',
  'fonts',
  'skins'
));
gulp.task('build', gulp.series('clean', 'dist'));
gulp.task('dev', gulp.series('build'));
gulp.task('default', gulp.series('dev'));
