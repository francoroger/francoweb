var gulp = require("gulp");
var babel = require("gulp-babel");
var rollup = require("gulp-rollup");
var Glob = require("glob-fs");
var path = require("path");

const config = {
  examples: {
    source: 'resources/src/examples',
    build: 'public/assets/examples'
  },
  scripts: {
    source: 'resources/src/es',
    build: 'public/assets/js'
  }
};

// Scripts (ES) ****************************************************************
gulp.task("scripts", (done) => {
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

  return gulp.src(`${config.scripts.source}/**/*.js`)
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
    .pipe(gulp.dest(`${config.scripts.build}`));
});

// Scripts (examples) **********************************************************
gulp.task('examples:scripts', (done) => {
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

  return gulp.src(`${config.examples.source}/es/**/*.js`)
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
    .pipe(gulp.dest(`${config.examples.build}/js`));
});

//Default **********************************************************************
gulp.task("default", gulp.series('scripts', 'examples:scripts'));
