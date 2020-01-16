# Rakonto

Rakonto is a WordPress plugin, the purpose of which is to allow authors of WordPress posts to publish a mathematical
hash of their content (kind of like a digital "fingerprint") on the Litecoin blockchain. That content may then be
verified by a third party as not having been deceptively edited by comparing a hash of the contents of a tagged
container element on a live page to the stored hash on the blockchain. In the event that a site's content author does
make an edit to a post through the WordPress user interface, Rakonto will submit a new hash to the blockchain, ensuring
that the ability to edit content is maintained in a verifiable manner.

For example, imagine a politician has posted a multitude of articles on his web site about his love for New York-style
pizza. But later the Pizza Lover's Alliance of Chicago offers that politician a substantial sum to "evolve" in his
viewpoint toward pizza, and to advocate for subsidies for pizza restaurants that specialize in deep dish.  If the
politician were to edit his site in order to mask the fact that his view has "evolved", how would you be able to tell?
Rakonto is how: Its purpose is to hold content creators accountable for their edits.

Because the data hash from any WordPress post is stored on the blockchain, its permanence is certain, as no data can be
altered or removed from blockchain systems such as Litecoin by design.

## Current project status

Rakonto is currently considered to be in a **beta** state. As such, it is configured to use the Litecoin **testnet**.
The testnet, as the name implies, is for testing purposes and transactions on the testnet do not use real money.

To get an address for use with the plugin, [click here](http://liteaddress.org/?testnet=true). Copy down the generated
address and private key (under the QR codes). Now go to [this faucet](http://testnet.thrasher.io/) and in the SEND
section, paste your address and specify an amount of between 1 to 10. Note that 1 coin should be plenty as each complete
Rakonto transaction costs 0.00115 LTC so 1 LTC is enough for 869 transactions / posts.

You can also DM us on [Twitter](https://twitter.com/RakontoHQ) or [Reddit](https://reddit.com/r/rakonto) with your
Litecoin address and we can send you some testnet coins.

## Installation

1. [*] Upload the contents of this repository to the `/wp-content/plugins/` directory of your WordPress installation, as
   per standard WordPress plugin procedures.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Configure the plugin via the menu located at `Settings > Rakonto` within the WordPress admin pages. Information about
   configuration follows.

[*] An alternative method is to use [GitHub Updater](https://github.com/afragen/github-updater). This is a great tool to
keep your Rakonto plugin up-to-date.

## Configuration

Rakonto may be configured to work in two fundamental modes: with a global Litecoin address, which will be used by all
authors who publish articles within the system, or via individual Litecoin addresses, which are specified for each
invidual user via the `Users` admin menu. 

Note that when Rakonto moves to **mainnet** it is recommended that Rakonto be configured to use individual addresses as
opposed to the global method, as the use of the global method requires that the private key for the Litecoin address
used be stored in the database.  However, using the global method is much simpler and if a malicious user has access to
your sites database, you have bigger problems than a compromised testnet wallet!  When using the individual method,
users are prompted to input the private key for their assigned address after publishing a WordPress post, and that key
is intentionally not saved within the database.

You may select which method you would like to use with the plugin via the `Settings > Rakonto` menu of the WordPress
admin pages. If you opt to use the global method, you may use the same menu to input your global address and private
key. If you opt to use the individual method (by disabling the global Litecoin address option) then click on the `Edit`
link next to the user you would like to configure within the WordPress `Users` configuration page, and scroll down to
the `Litecoin Address` section of that page to input the user's Litecoin address. After posting an article, the user
will be prompted to enter their private key.

Lastly, we recommend using Litecoin wallet(s) which are only used for Rakonto. The amount of LTC needed to use the
plugin is tiny, as mentioned above. So when the project moves to **mainnet**, we don't recommend using a wallet with you
main LTC holdings!

## Viewing the Verification

We have built a companion web application for Rakonto, which is publicly accessible at
[https://explorer.rakonto.net](https://explorer.rakonto.net), named Rakonto Explorer. All transactions posted through
the Rakonto system are viewable, and the associated content therefore verifiable, directly through that interface.

Additional information about the project in total is available at [https://rakonto.net](https://rakonto.net).

## Support

We are more than happy to help. We can be reached via [Twitter](https://twitter.com/RakontoHQ) and
[Reddit](https://reddit.com/r/rakonto) (private or public).

The biggest issue we have seen during development is that of other WordPress plugins or themes. Compatibility between
various plugins and themes is a longstanding issue with WordPress sites, but if you run into problems we will do all we
can to try and resolve.


<!-- vim: set tw=120: -->
