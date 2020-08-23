const path = require("path");

const mode = "production";

const jsSrc = "./assets/src/index.js";

const distDir = path.join(__dirname, "assets", "dist");
const testDir = path.join(__dirname, "tests", "acceptance", "assets");
const exportDir = mode === "production" ? distDir : testDir;

module.exports = {
  entry: [jsSrc],
  output: {
    path: exportDir,
    filename: "saveyour.js",
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: [/node_modules/, /vendor/],
        loader: "babel-loader",
      },
    ],
  },
  resolve: {
    extensions: [".json", ".js", ".jsx"],
  },
  devtool: "source-map",
  devServer: {
    contentBase: path.join(__dirname, "/dist/"),
    inline: true,
    host: "localhost",
    port: 8080,
  },
};
