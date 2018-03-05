# Rakonto

Rakonto is a WordPress plugin, the purpose of which is to allow authors of WordPress posts to publish a mathematical 
hash of their content (kind of like a digital "fingerprint") on the Litecoin blockchain. That content may then be 
verified by a third party as not having been deceptively edited by comparing a hash of the contents of a tagged 
container element on a live page to the stored hash on the blockchain. In the event that a site's content author does 
make an edit to a post through the WordPress user interface, Rakonto will submit a new hash to the blockchain, ensuring 
that the ability to edit content is maintained in a verifiable manner.

For example, imagine a politician has posted a multitude of articles on his web site about his love for New 
York-style pizza. But later the Pizza Lover's Alliance of Chicago offers that politician a substantial sum to "evolve" 
in his viewpoint toward pizza, and to advocate for subsidies for pizza restaurants that specialize in deep dish. 
If the politician were to edit his site in order to mask the fact that his view has "evolved", how would you be able 
to tell? Rakonto is how: Its purpose is to hold content creators accountable for their edits.

Because the data hash from any WordPress post is stored on the blockchain, its permanence is certain, as no data can 
be altered or removed from blockchain systems such as Litecoin by design.

## Currunt project status

Rakonto is currently considered to be in a beta state. As such, it is configured to use the Litecoin testnet. The
testnet, as the name implies, is for testing purposes and transactions on the testnet do not use real money.

To get an address for use with the plugin, [click here](http://liteaddress.org/?testnet=true). Copy down the generated
address and private key (under the QR codes). Now go to [this faucet](http://testnet.thrasher.io/) and in the SEND
section, paste your address and specify an amount of between 1 to 10. Note that 1 coin should be plenty as each complete
Rakonto transaction costs 0.00115 LTC so 1 LTC is enough for 869 transactions / posts.

## Installation

1. Upload the contents of this repository to the `/wp-content/plugins/` directory of your WordPress installation, as
per standard WordPress plugin procedures.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Configure the plugin via the menu located at `Settings > Rakonto` within the WordPress admin pages. Information
about configuration follows.

## Configuration

Rakonto may be configured to work in two fundamental modes: with a global Litecoin address, which will be used by all
authors who publish articles within the system, or via individual Litecoin addresses, which are specified for each
invidual user via the `Users` admin menu. 

Note that it is recommended that Rakonto be configured to use individual addresses as opposed to the global method, as
the use of the global method requires that the private key for the Litecoin address used be stored in the database. 
When using the individual method, users are prompted to input the private key for their assigned address after 
publishing a WordPress post, and that key is intentionally not saved within the database.

You may select which method you would like to use with the plugin via the `Settings > Rakonto` menu of the WordPress 
admin pages. If you opt to use the global method, you may use the same menu to input your global address and private
key. If you opt to use the individual method (by disabling the global Litecoin address option) then click on the `Edit`
link next to the user you would like to configure within the WordPress `Users` configuration page, and scroll down to 
the `Litecoin Address` section of that page to input the user's Litecoin address. After posting an article, the user
will be prompted to enter their private key.

## Viewing the Verification

We have built a companion web application for Rakonto, which is publicly accessible at
[https://explorer.rakonto.net](https://explorer.rakonto.net), named Rakonto Explorer. All transactions posted through
the Rakonto system are viewable, and the associated content therefore verifiable, directly through that interface.

Additional information about the project in total is available at [https://rakonto.net](https://rakonto.net).

## FAQs

### Why did you create Rakonto?

We believe that integrating blockchain technology in communications solves an important issue: increasing long-term 
trust in the content that organizations publish.

### Who is it for?

When legal and regulatory norms catch up to the state of the art,  we anticipate that Rakonto could be useful in any 
scenario that requires an independent audit trail of what was published and when. This includes:

- Regulated industries (e.g., finance, health, energy)
- Journalism (e.g., "proof-of-scoop")
- Government communications
- Community relations

### How can I be assured that a post is properly notarized and authentic?

We have created a custom [blockchain explorer](https://explorer.rakonto.net) to enable 
users to check current and previous versions of content. This site is also open source for transparency and we also 
have made available standalone [https://rakonto.net/tools](tools) to allow anyone to independently verify the content 
from the recorded hash in a transaction.

### Why did you choose to build a WordPress plugin?

We felt that this was the way we could do the most good for the most people *quickly*. If this effort is successful, 
and where there is demand, we will release plugins for other platforms.

### Why is Rakonto open source?

We want people who are interested in the Secure Narratives thesis to help develop Rakonto. The best way to do this is 
to make the code available for everyone to inspect, adapt, improve and implement.

### Why did you choose Litecoin?

Litecoin is a well-established and secure fork of Bitcoin, with transaction fees that are more appropriate for an 
application such as this. Low fees are extremely important for this kind of system.

### Will there be a pre-sale for the Rakonto ICO?

There will *not* be a Rakonto ICO. In fact, there are a lot of reasons why an application like this should not have its 
own coin. This is a not-for-profit venture, we just want to do some good.
