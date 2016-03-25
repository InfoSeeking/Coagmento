var Layout = function() {};
Layout.prototype.preInit = function(root) {
	this.root = root;
};
Layout.prototype.postInit = function(root) {};
Layout.prototype.add = function(html) {
	this.root.prepend(html);
};
// Called when a model is removed.
Layout.prototype.refresh = function() {};
Layout.prototype.destroy = function() {};

Layout.prototype.getTemplate = function(dataType) {
	var selector = '[data-template=' + dataType + '][data-layout=' + this.key + ']';
	if (!this.templateCache) this.templateCache = {};
	if (!this.templateCache[selector]) {
		console.log(selector);
		this.templateCache[selector] = _.template($(selector).html());
	}
	return this.templateCache[selector];
};

// Any layout which does not require any special setup or update
// on add/remove can simply extend the default layout and provide 
// a unique key like GridLayout and ListLayout.
var GridLayout = function() {
	this.key = 'grid';
};
GridLayout.prototype = Object.create(Layout.prototype);
GridLayout.prototype.constructor = Layout;

var ListLayout = function() {
	this.key = 'list';
};
ListLayout.prototype = new Layout();
ListLayout.prototype.constructor = Layout;

// CoverflowLayout requires special setup and behavior on refresh.
var CoverflowLayout = function() {
	this.key = 'coverflow';
};

CoverflowLayout.prototype = Object.create(Layout.prototype);
CoverflowLayout.constructor = Layout;

CoverflowLayout.prototype.preInit = function(root) {
	this.root = root;
	this.container = $("<div id='coverflow-container'>");
	this.root.append(this.container);
};

CoverflowLayout.prototype.postInit = function() {
	this.coverflow = this.container.coverflow();
	this.refresh();
}

CoverflowLayout.prototype.refresh = function() {
	this.coverflow.data('vanderlee-coverflow').refresh(500);
};

CoverflowLayout.prototype.add = function(html) {
	this.container.prepend(html);
	if (this.coverflow) this.refresh();
};

var ThreeDLayout = function() {
	this.key = 'three-d';
}

ThreeDLayout.prototype = Object.create(Layout.prototype);

ThreeDLayout.prototype.postInit = function(root) {
	this.root.threeD('init');
};

ThreeDLayout.prototype.destroy = function() {
	this.root.threeD('destroy');
};

var SidebarLayout = function() {
	this.key = 'sidebar';
};

SidebarLayout.prototype = Object.create(Layout.prototype);
SidebarLayout.constructor = Layout;